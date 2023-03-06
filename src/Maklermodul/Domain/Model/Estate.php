<?php

declare(strict_types=1);

/*
 * maklermodul bundle for Contao Open Source CMS
 *
 * Copyright (c) 2023 pdir / digital agentur // pdir GmbH
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

namespace Pdir\MaklermodulBundle\Maklermodul\Domain\Model;

use Contao\System;
use Pdir\MaklermodulBundle\Maklermodul\ContaoImpl\Domain\Model\IndexConfigInterface;

class Estate
{
    private $rawData;

    public function __construct($rawData = null)
    {
        $this->rawData = $rawData;
    }

    public function getValueOf($ident)
    {
        if (isset($this->rawData[$ident])) {
            return $this->rawData[$ident];
        }

        $pointIdent = str_replace('/', '.', $ident);

        if (isset($this->rawData[$pointIdent])) {
            return $this->rawData[$pointIdent];
        }

        return null;
    }

    public function checkIfKeyExists($ident)
    {
        if (isset($this->rawData[$ident])) {
            return true;
        }

        return false;
    }

    public function getUriIdentifier()
    {
        $strPrefix = '';
        $strAlias = '';
        $strSuffix = '';

        $prefix = System::getContainer()->getParameter('pdir_maklermodul.aliasPrefix');
        $alias = System::getContainer()->getParameter('pdir_maklermodul.alias');
        $suffix = System::getContainer()->getParameter('pdir_maklermodul.aliasSuffix');

        if (null !== $prefix) {
            $strPrefix = (string) $this->getValueOf($prefix);
        }

        if (null !== $alias) {
            $strAlias = (string) $this->getValueOf($alias);
        }

        if (null !== $suffix) {
            $strSuffix = (string) $this->getValueOf($suffix);
        }

        return $this->sanizeFileName(
            sprintf('%s-%s-%s', $strPrefix, $strAlias, $strSuffix)
        );
    }

    public function getCssFilterString(IndexConfigInterface $config)
    {
        $returnValue = $this->getValueOf('css-filter-class-string');

        if (null === $returnValue) {
            $classes = $this->resolveCssClasses($this, $config, ['estate']);
            $returnValue = implode(' ', $classes);
        }

        return $returnValue;
    }

    public static function sanizeFileName($strSource)
    {
        $validAliasCharacters = System::getContainer()->getParameter('pdir_maklermodul.validAliasCharacters');
        $delimiter = System::getContainer()->getParameter('pdir_maklermodul.aliasDelimiter');
        $locale = System::getContainer()->getParameter('pdir_maklermodul.aliasLocale');

        $options = [
            'delimiter' => $delimiter ?: '-',
            'validChars' => $validAliasCharacters,
            'locale' => $locale ?: '',
        ];

        if (is_object($strSource)) {
            $strSource = (string)$strSource;
        }

        if (null === $strSource) {
            $strSource = 'no-title-exists';
        }

        // generate slug
        $strValue = System::getContainer()->get('contao.slug')->generate($strSource, $options);

        // remove the prefix if "id-" is set
        if (false !== strpos($strValue, 'id-') && !is_numeric($strSubstr = substr($strValue, 3))) {
            $strValue = substr($strValue, 3);
        }

        return $strValue;
    }

    public function getFieldsIterator()
    {
        return new \ArrayIterator($this->rawData);
    }

    private function resolveCssClasses(self $estate, IndexConfigInterface $config, $startClasses = [])
    {
        $returnValue = $startClasses;

        $options = [
            'delimiter' => '-',
            'validChars' => '0-9a-z_',
            'locale' => '',
        ];

        foreach ($config->getColumnConfig() as $columnConfig) {
            if ($columnConfig->isFilterAble()) {
                $key = $columnConfig->getSourceIdentifier();
                $value = $estate->getValueOf($key);
                $class = $columnConfig->getCssClassOfValue($key, $value);

                if (null !== $class) {
                    $returnValue[] = System::getContainer()->get('contao.slug')->generate($class, $options);
                }
            }
        }

        return $returnValue;
    }
}
