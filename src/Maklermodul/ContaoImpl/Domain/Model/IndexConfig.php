<?php

/*
 * maklermodul bundle for Contao Open Source CMS
 *
 * Copyright (c) 2019 pdir / digital agentur // pdir GmbH
 *
 * @package    maklermodul-bundle
 * @link       https://www.maklermodul.de
 * @license    proprietary / pdir license - All-rights-reserved - commercial extension
 * @author     Mathias Arzberger <develop@pdir.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Namespace.
 */

namespace Pdir\MaklermodulBundle\Maklermodul\ContaoImpl\Domain\Model;

use Pdir\MaklermodulBundle\Maklermodul\Domain\Model\ColumnConfig;
use Pdir\MaklermodulBundle\Maklermodul\Domain\Model\Estate;

class IndexConfig implements IndexConfigInterface
{
    /**
     * @var array
     */
    private $resultSet;

    public function __construct($resultSet)
    {
        $this->resultSet = $resultSet;
    }

    public function get($key)
    {
        return $this->resultSet[$key];
    }

    public function getUid()
    {
        return $this->resultSet['id'];
    }

    public function getDetailViewUri(Estate $estate)
    {
        throw new \Exception('deprecated - not yet implemented');
    }

    public function getReaderPageId()
    {
        return $this->resultSet['immo_readerPage'];
    }

    public function getColumnConfig()
    {
        if (empty($this->resultSet['immo_listContent'])) {
            $configArray = $this->getDefaultConfig();
        } else {
            $configArray = $this->getCustomConfig(
                $this->resultSet['immo_listContent'],
                $this->resultSet['immo_listFilter']
            );
        }

        return $this->convertToColumnConfig($configArray);
    }

    public function getFilterColumnConfig()
    {
        if (empty($this->resultSet['immo_listFilter'])) {
            $configArray = $this->getDefaultFilterColumnsConfig();
        } else {
            $configArray = $this->getCustomColumnsFilterConfig(
                $this->getColumnConfig(),
                explode(PHP_EOL, $this->resultSet['immo_listFilter'])
            );
        }

        return $configArray;
    }

    public function getStorageFileUri()
    {
        return $this->resultSet['immo_actIndexFile'];
    }

    public function setStorageFileUri($newUri)
    {
        $this->resultSet['immo_actIndexFile'] = $newUri;
    }

    public function getConditionsConfig()
    {
        $condArr = [];
        $arr = explode("\n", $this->resultSet['immo_listCondition']);

        foreach ($arr as $cond) {
            $str = html_entity_decode($cond);
            if (false !== strpos($str, '!=')) {
                $result = explode('!=', $str);
                $condArr['unequal'][$result[0]][] = $result[1];
            } elseif (false !== strpos($str, '=')) {
                $result = explode('=', $str);
                $condArr['equal'][$result[0]][] = $result[1];
            }
        }

        return $condArr;
    }

    public function getCompatibilityMode()
    {
        return $this->resultSet['makler_compatibilityMode'];
    }

    public function getListInSitemap()
    {
        return $this->resultSet['immo_listInSitemap'];
    }

    private function getDefaultConfig()
    {
        $configArray = [
            ['freitexte.objekttitel', false],
            ['flaechen.anzahl_zimmer', true],
            ['objektkategorie.nutzungsart.@WAZ', true],
            ['objektkategorie.nutzungsart.@GEWERBE', true],
            ['objektkategorie.nutzungsart.@ANLAGE', true],
            ['objektkategorie.nutzungsart.@WOHNEN', true],
            ['flaechen.wohnflaeche', true],
            ['flaechen.grundstuecksflaeche', true],
            ['anhaenge.anhang.#1.@gruppe', false],
            ['anhaenge.anhang.#1.daten.pfad', false],
            ['anhaenge.anhang.#1.format', false],
            ['anhaenge.anhang.#1.anhangtitel', false],
            ['geo.plz', false],
            ['geo.ort', true],
            ['verwaltung_techn.objektnr_extern', false],
        ];

        return $configArray;
    }

    private function getCustomConfig($fieldConfig, $filterConfig)
    {
        $returnValue = $this->parseDisplayFields($fieldConfig);
        $returnValue = $this->configureFilterSettings($returnValue, $filterConfig);

        return $returnValue;
    }

    private function parseDisplayFields($fieldConfig)
    {
        $returnValue = [];
        $lines = explode("\n", $fieldConfig);

        foreach ($lines as $line) {
            $line = $this->substitudeEncodedSigns($line);
            $returnValue[] = [$line, false];
        }

        return $returnValue;
    }

    private function substitudeEncodedSigns($str)
    {
        return html_entity_decode($str);
    }

    private function configureFilterSettings($displayFields, $filterConfig)
    {
        $lines = explode("\n", $filterConfig);

        foreach ($lines as $line) {
            $line = $this->substitudeEncodedSigns($line);
            foreach ($displayFields as &$fieldConfig) {
                if ($line === $fieldConfig[0]) {
                    $fieldConfig[1] = true;
                }
            }
        }

        return $displayFields;
    }

    private function convertToColumnConfig($configArray)
    {
        $returnValue = [];

        foreach ($configArray as $ident) {
            $returnValue[] = new ColumnConfig($ident[0], $ident[1]);
        }

        return $returnValue;
    }

    private function getDefaultFilterColumnsConfig()
    {
        $returnValue = [];
        $columns = $this->getColumnConfig();

        foreach ($columns as $column) {
            if ($column->isFilterable()) {
                $returnValue[] = $column;
            }
        }

        return $returnValue;
    }

    private function getCustomColumnsFilterConfig($allColumns, $filterFields)
    {
        $returnValue = [];

        foreach ($filterFields as $field) {
            foreach ($allColumns as $column) {
                if ($column->getSourceIdentifier() === $field) {
                    $returnValue[] = $column;
                }
            }
        }

        return $returnValue;
    }
}
