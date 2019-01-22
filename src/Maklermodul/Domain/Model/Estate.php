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
        $title = sprintf('%s-%s', $this->getValueOf('freitexte.objekttitel'), $this->getValueOf('verwaltung_techn.objektnr_extern'));
        // @todo Individuelle Angabe des eindeutigen Bezeichners implementieren
        return $this->sanizeFileName($title);
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
        $target = str_replace(' ', '-', $source);
        $target = str_replace('!', '-', $target);
        $target = str_replace('.', '-', $target);
        $target = str_replace(',', '-', $target);
        $target = str_replace(':', '-', $target);
        $target = str_replace('/', '', $target);
        $target = str_replace('°', '-grad-', $target);
        $target = str_replace('\'', '', $target);
        $target = str_replace('"', '', $target);
        $target = str_replace('&', '-und-', $target);
        $target = str_replace('?', '', $target);
        $target = str_replace('(', '-', $target);
        $target = str_replace(')', '-', $target);
        $target = str_replace("\n", '-', $target);
        $target = str_replace('@', '-', $target);
        $target = str_replace('#', '-', $target);
        $target = str_replace('´', '-', $target);

        $target = str_replace('á', 'a', $target);
        $target = str_replace('é', 'e', $target);
        $target = str_replace('ä', 'ae', $target);
        $target = str_replace('ö', 'oe', $target);
        $target = str_replace('ü', 'ue', $target);
        $target = str_replace('Ä', 'Ae', $target);
        $target = str_replace('Ö', 'Oe', $target);
        $target = str_replace('Ü', 'Ue', $target);
        $target = str_replace('ß', 'ss', $target);

        $target = str_replace('$', '', $target);
        $target = str_replace('§', '', $target);
        $target = str_replace('%', '', $target);
        $target = str_replace('"', '', $target);
        $target = str_replace('=', '', $target);
        $target = str_replace('`', '', $target);
        $target = str_replace('²', '', $target);
        $target = str_replace('³', '', $target);
        $target = str_replace('*', '', $target);
        $target = str_replace('+', '', $target);
        $target = str_replace('&#x85;', '', $target);

        // cyrillic fix
        if (preg_match('/[\p{Cyrillic}]/u', $target)) {
            $target = preg_replace('/[\x{0410}-\x{042F}]+.*[\x{0410}-\x{042F}]+/iu', '', $target);
        }

        $target = str_replace('----', '-', $target);
        $target = str_replace('---', '-', $target);
        $target = str_replace('--', '-', $target);

        if (0 === strpos($target, '-')) {
            $target = substr($target, 1);
        }

        $length = count($target);
        if (strpos($target, '-', $length - 1) === $length - 1) {
            $target = substr($target, 0, $length - 2);
        }

        if ('-' === substr($target, -1)) {
            $target = substr($target, 0, -1);
        }

        if ('-' === substr($target, 1)) {
            $target = substr($target, 0, -1);
        }

        return strtolower($target);
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
