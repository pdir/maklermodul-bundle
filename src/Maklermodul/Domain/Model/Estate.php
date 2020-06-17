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

namespace Pdir\MaklermodulBundle\Maklermodul\Domain\Model;

use Contao\StringUtil;
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

        if(null !== $prefix)
            $strPrefix = (string) $this->getValueOf($prefix);

        if(null !== $alias)
            $strAlias = (string) $this->getValueOf($alias);

        if(null !== $suffix)
            $strSuffix = (string) $this->getValueOf($suffix);

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

    public static function sanizeFileName($source)
    {
        // remove the prefix if "id-" is set
        $strString = StringUtil::generateAlias($source);
        if (strpos($strString,"id-")!==false && !is_numeric($strSubstr = substr($strString, 3)))
        {
            $strString = substr($strString, 3);
        }
        return $strString;
    }

    public function getFieldsIterator()
    {
        return new \ArrayIterator($this->rawData);
    }

    private function resolveCssClasses(self $estate, IndexConfigInterface $config, $startClasses = [])
    {
        $returnValue = $startClasses;

        foreach ($config->getColumnConfig() as $columnConfig) {
            if ($columnConfig->isFilterAble()) {
                $key = $columnConfig->getSourceIdentifier();
                $value = $estate->getValueOf($key);
                $class = $columnConfig->getCssClassOfValue($key, $value);
                if (null !== $class) {
                    $returnValue[] = $this->sanizeFileName($class);
                }
            }
        }

        return $returnValue;
    }
}
