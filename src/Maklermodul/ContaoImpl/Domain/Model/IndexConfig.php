<?php

/**
 * maklermodul for Contao Open Source CMS
 *
 * Copyright (C) 2017 pdir / digital agentur <develop@pdir.de>
 *
 * @package    maklermodul
 * @link       https://www.maklermodul.de
 * @license    pdir license - All-rights-reserved - commercial extension
 * @author     pdir GmbH <develop@pdir.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Namespace
 */
namespace Pdir\MaklermodulBundle\Maklermodul\ContaoImpl\Domain\Model;

use Pdir\MaklermodulBundle\Maklermodul\Domain\Model\IndexConfigInterface;
use Pdir\MaklermodulBundle\Maklermodul\Domain\Model\ColumnConfig;
use Pdir\MaklermodulBundle\Maklermodul\Domain\Model\Estate;

class IndexConfig implements IndexConfigInterface {

	/**
	 * @var array
	 */
	private $resultSet;

	public function __construct($resultSet) {
		$this->resultSet = $resultSet;
	}

	public function getUid() {
		return $this->resultSet['id'];
	}

	public function getDetailViewUri(Estate $estate) {
		throw new \Exception("deprecated - not yet implemented");
	}

	public function getReaderPageId() {
		return $this->resultSet['immo_readerPage'];
	}

	public function getColumnConfig() {
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

    private function getDefaultConfig() {
		$configArray = array (
			array('freitexte.objekttitel', false),
			array('flaechen.anzahl_zimmer', true),
			array('objektkategorie.nutzungsart.@WAZ', true),
			array('objektkategorie.nutzungsart.@GEWERBE', true),
			array('objektkategorie.nutzungsart.@ANLAGE', true),
			array('objektkategorie.nutzungsart.@WOHNEN', true),
			array('flaechen.wohnflaeche', true),
			array('flaechen.grundstuecksflaeche', true),
			array('anhaenge.anhang.#1.@gruppe', false),
			array('anhaenge.anhang.#1.daten.pfad', false),
			array('anhaenge.anhang.#1.format', false),
			array('anhaenge.anhang.#1.anhangtitel', false),
			array('geo.plz', false),
			array('geo.ort', true),
			array('verwaltung_techn.objektnr_extern', false)
		);

	    return $configArray;
	}

    private function getCustomConfig($fieldConfig, $filterConfig) {
        $returnValue = $this->parseDisplayFields($fieldConfig);
        $returnValue = $this->configureFilterSettings($returnValue, $filterConfig);

        return $returnValue;
    }

    private function parseDisplayFields($fieldConfig) {
        $returnValue = array();
        $lines = explode("\n", $fieldConfig);

        foreach ($lines as $line) {
            $line = $this->substitudeEncodedSigns($line);
            $returnValue[] = array($line, false);
        }

        return $returnValue;
    }

    private function substitudeEncodedSigns($str) {
        return html_entity_decode($str);
    }

    private function configureFilterSettings($displayFields, $filterConfig) {
        $lines = explode("\n", $filterConfig);

        foreach ($lines as $line) {
            $line = $this->substitudeEncodedSigns($line);
            foreach ($displayFields as &$fieldConfig) {
                if ($line == $fieldConfig[0]) {
                    $fieldConfig[1] = true;
                }
            }
        }

        return $displayFields;
    }

    private function convertToColumnConfig($configArray) {
        $returnValue = array();

        foreach ($configArray as $ident) {
            $returnValue[] = new ColumnConfig($ident[0], $ident[1]);
        }

        return $returnValue;
    }

    public function getFilterColumnConfig() {
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

    private function getDefaultFilterColumnsConfig() {
        $returnValue = array();
        $columns = $this->getColumnConfig();

        foreach ($columns as $column) {
            if ($column->isFilterable()) {
                $returnValue[] = $column;
            }
        }

        return $returnValue;
    }

    private function getCustomColumnsFilterConfig($allColumns, $filterFields) {
        $returnValue = array();

        foreach ($filterFields as $field) {
            foreach ($allColumns as $column) {
                if ($column->getSourceIdentifier() == $field) {
                    $returnValue[] = $column;
                }
            }
        }

        return $returnValue;
    }

	public function getStorageFileUri() {
		return $this->resultSet['immo_actIndexFile'];
	}

	public function setStorageFileUri($newUri) {
		$this->resultSet['immo_actIndexFile'] = $newUri;
	}

	public function getImageDimensions() {
		if(isset($this->resultSet['immo_listImageWidth']) && isset($this->resultSet['immo_listImageHeight']) &&
			$this->resultSet['immo_listImageWidth'] != '' && $this->resultSet['immo_listImageHeight'] != '') {
			return array(
				'width' => $this->resultSet['immo_listImageWidth'],
				'height' => $this->resultSet['immo_listImageHeight'],
				'mode'	=>  $this->resultSet['immo_listImageMode']
			);
		} else {
			return array(
				'width' => 293,
				'height' => 220,
				'mode' => 'proportional'
			);
		}
	}

	public function getConditionsConfig() {
		$condArr = array();
		$arr = explode("\n", $this->resultSet['immo_listCondition']);

		foreach( $arr as $cond) {
			$str = html_entity_decode($cond);
			if(strpos($str,"!=")!==false) {
				$result = explode("!=", $str);
				$condArr['unequal'][$result[0]][] = $result[1];
			} elseif(strpos($str,"=")!==false) {
				$result = explode("=", $str);
				$condArr['equal'][$result[0]][] = $result[1];
			}
		}

		return $condArr;
	}

    public function getCompatibilityMode() {
        return $this->resultSet['makler_compatibilityMode'];
    }

    public function getListInSitemap() {
        return $this->resultSet['immo_listInSitemap'];
    }
}
