<?php

declare(strict_types=1);

/*
 * maklermodul bundle for Contao Open Source CMS
 *
 * Copyright (c) 2022 pdir / digital agentur // pdir GmbH
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

use Pdir\MaklermodulBundle\Maklermodul\ContaoImpl\StaticDIC;

class ColumnConfig
{
    private $sourceIdentifier;
    private $filterable;
    private $cssClassMapping;

    public function __construct($sourceIdentifier, $filterable = false)
    {
        $this->sourceIdentifier = $sourceIdentifier;
        $this->filterable = $filterable;
        $this->cssClassMapping = StaticDIC::getCssFilterClassMapping();
    }

    public function getSourceIdentifier()
    {
        return $this->sourceIdentifier;
    }

    public function isFilterable()
    {
        return $this->filterable;
    }

    public function getCssClassOfValue($key, $value)
    {
        $returnValue = $this->findCssClassDataOfValue($key, $value);

        if (\is_array($returnValue)) {
            $returnValue = sprintf('%s-%s', $returnValue[0], $returnValue[1]);
        }

        return $returnValue;
    }

    public function getFilterConfig($value)
    {
        $key = $this->getSourceIdentifier();
        $cssClassData = $this->findCssClassDataOfValue($key, $value);

        if (\is_array($cssClassData)) {
            return [
                'group' => $cssClassData[0],
                'value' => $cssClassData[1],
                'name' => $cssClassData[1],
            ];
        }

        return [
            'name' => $value,
            'value' => $key,
        ];
    }

    private function findCssClassDataOfValue($key, $value)
    {
        $returnValue = $value;

        if (isset($this->cssClassMapping[$key])) {
            $function = $this->cssClassMapping[$key];
            $returnValue = \call_user_func_array([$this, $function], [$returnValue]);
        }

        if (true === $returnValue) {
            $value = str_replace('.@', '', substr($key, strpos($key, '.@')));
            $key = substr($key, 0, strpos($key, '.@'));
            $returnValue = [$key, $value];
        } elseif (!empty($returnValue) && false !== $returnValue && 'false' !== strtolower($returnValue)) {
            $returnValue = [$key, $returnValue];
        } else {
            $returnValue = null;
        }

        return $returnValue;
    }

    private function cssMapBoolean($value)
    {
        if (null === $value) {
            return null;
        }

        $value = strtolower($value);

        if (true === $value || 'true' === $value || 1 === $value || 1 === (int) $value) {
            return true;
        }

        return false;
    }

    private function cssMapDefault($value)
    {
        return $value;
    }

    private function cssMapNumber($value)
    {
        $LocaleInfo = localeconv();
        $value = str_replace($LocaleInfo['mon_thousands_sep'], '', $value);
        $value = str_replace($LocaleInfo['mon_decimal_point'], '.', $value);

        return round((int) $value, 0);
    }

    private function cssMapRange1($value)
    {
        return $this->getRange($value, 1);
    }

    private function cssMapRange3($value)
    {
        return $this->getRange($value, 3);
    }

    private function cssMapRange10($value)
    {
        return $this->getRange($value, 10);
    }

    private function cssMapRange50($value)
    {
        return $this->getRange($value, 50);
    }

    private function cssMapRange100($value)
    {
        return $this->getRange($value, 100);
    }

    private function cssMapRange200($value)
    {
        return $this->getRange($value, 200);
    }

    private function cssMapRange1000($value)
    {
        return $this->getRange($value, 1000);
    }

    private function cssMapRange10000($value)
    {
        return $this->getRange($value, 10000);
    }

    private function cssMapRange50000($value)
    {
        return $this->getRange($value, 50000);
    }

    private function cssMapRange100000($value)
    {
        return $this->getRange($value, 100000);
    }

    private function getRange($value, $steps)
    {
        $value = $this->cssMapNumber($value);
        $multi = $value / $steps;
        $low = (int) $multi;
        $high = $low + 1;

        $low *= $steps;
        $high *= $steps;

        if (0 === $low) {
            $low = 1;
        }

        return sprintf('%s-%s', $low, $high);
    }

    private function cssMapFloor1($value)
    {
        return $this->getFloorRange($value, 1);
    }

    private function cssMapFloor3($value)
    {
        return $this->getFloorRange($value, 3);
    }

    private function cssMapFloor10($value)
    {
        return $this->getFloorRange($value, 10);
    }

    private function cssMapFloor50($value)
    {
        return $this->getFloorRange($value, 50);
    }

    private function cssMapFloor100($value)
    {
        return $this->getFloorRange($value, 100);
    }

    private function cssMapFloor200($value)
    {
        return $this->getFloorRange($value, 200);
    }

    private function cssMapFloor1000($value)
    {
        return $this->getFloorRange($value, 1000);
    }

    private function cssMapFloor10000($value)
    {
        return $this->getFloorRange($value, 10000);
    }

    private function cssMapFloor50000($value)
    {
        return $this->getFloorRange($value, 50000);
    }

    private function cssMapFloor100000($value)
    {
        return $this->getFloorRange($value, 100000);
    }

    private function getFloorRange($value, $steps)
    {
        $value = $this->cssMapFloorNumber($value);
        $multi = $value / $steps;
        $low = floor($multi);
        $high = $low + 1;

        $low *= $steps;
        $high *= $steps;

        if (0 === $low) {
            $low = 1;
        }

        return sprintf('%s-%s', $low, $high);
    }

    private function cssMapFloorNumber($value)
    {
        $LocaleInfo = localeconv();
        $value = str_replace($LocaleInfo['mon_thousands_sep'], '', $value);
        $value = str_replace($LocaleInfo['mon_decimal_point'], '.', $value);

        return floor($value);
    }
}
