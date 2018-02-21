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
namespace Pdir\MaklermodulBundle\Maklermodul\Domain\Model;

use Pdir\MaklermodulBundle\Maklermodul\ContaoImpl\StaticDIC;

class ColumnConfig {

	private $sourceIdentifier;
	private $filterable;
	private $cssClassMapping;

	public function __construct($sourceIdentifier, $filterable = false) {
		$this->sourceIdentifier = $sourceIdentifier;
		$this->filterable = $filterable;
		$this->cssClassMapping = StaticDIC::getCssFilterClassMapping();
	}

	public function getSourceIdentifier() {
		return $this->sourceIdentifier;
	}

	public function isFilterable() {
		return $this->filterable;
	}

	public function getCssClassOfValue($key, $value) {
        $returnValue = $this->findCssClassDataOfValue($key, $value);

        if (is_array($returnValue)) {
            $returnValue = sprintf('%s-%s', $returnValue[0], $returnValue[1]);
        }

        return $returnValue;
    }

    private function findCssClassDataOfValue($key, $value) {
		$returnValue = $value;

		if (isset($this->cssClassMapping[$key])) {
			$function = $this->cssClassMapping[$key];
			$returnValue = call_user_func_array(array($this, $function), array($returnValue));
		}

		if ($returnValue === true) {
            $value = $key;
            $key = substr($key, 0, strpos($key,'.@'));
			$returnValue = array($key, $value);
		} elseif (!empty($returnValue) AND $returnValue != false AND strtolower($returnValue) != 'false') {
			$returnValue = array($key, $returnValue);
		}  else {
			$returnValue = null;
		}

		return $returnValue;
	}

    public function getFilterConfig($value) {
        $key = $this->getSourceIdentifier();
        $cssClassData = $this->findCssClassDataOfValue($key, $value);

        if (is_array($cssClassData)) {
            return array(
                'group' => $cssClassData[0],
                'value' => $cssClassData[1],
                'name' => $cssClassData[1]
            );
        } else {
            return array(
                'name'  => $value,
                'value' => $key
            );
        }
    }

	private function cssMapBoolean($value) {
		$value = strtolower($value);
		if ($value === true OR $value == 'true' OR $value == 1) {
			return true;
		} else {
			return false;
		}
	}

    private function cssMapDefault($value) {
        return $value;
    }

	private function cssMapNumber($value) {
		$LocaleInfo = localeconv();
	    $value = str_replace($LocaleInfo["mon_thousands_sep"] , "", $value);
    	$value = str_replace($LocaleInfo["mon_decimal_point"] , ".", $value);
		$value = round($value, 0);
		return $value;
	}

    private function cssMapRange1($value) {
        return $this->getRange($value, 1);
    }

	private function cssMapRange3($value) {
		return $this->getRange($value, 3);
	}

    private function cssMapRange10($value) {
        return $this->getRange($value, 10);
    }

	private function cssMapRange50($value) {
		return $this->getRange($value, 50);
	}

    private function cssMapRange100($value) {
        return $this->getRange($value, 100);
    }

	private function cssMapRange200($value) {
		return $this->getRange($value, 200);
	}

    private function cssMapRange1000($value) {
        return $this->getRange($value, 1000);
    }

    private function cssMapRange10000($value) {
    	return $this->getRange($value, 10000);
    }

    private function cssMapRange50000($value) {
    	return $this->getRange($value, 50000);
    }

    private function cssMapRange100000($value) {
    	return $this->getRange($value, 100000);
    }

	private function getRange($value, $steps) {
		$value = $this->cssMapNumber($value);
		$multi = $value / $steps;
		$low = intval($multi);
		$high = $low + 1;

		$low = $low * $steps;
		$high = $high * $steps;

		if ($low == 0) {
			$low = 1;
		}

		$returnValue = sprintf('%s-%s', $low, $high);
		return $returnValue;
	}

	private function cssMapFloor1($value) {
		return $this->getFloorRange($value, 1);
	}

	private function cssMapFloor3($value) {
		return $this->getFloorRange($value, 3);
	}

	private function cssMapFloor10($value) {
		return $this->getFloorRange($value, 10);
	}

	private function cssMapFloor50($value) {
		return $this->getFloorRange($value, 50);
	}

	private function cssMapFloor100($value) {
		return $this->getFloorRange($value, 100);
	}

	private function cssMapFloor200($value) {
		return $this->getFloorRange($value, 200);
	}

	private function cssMapFloor1000($value) {
		return $this->getFloorRange($value, 1000);
	}

	private function cssMapFloor10000($value) {
		return $this->getFloorRange($value, 10000);
	}

	private function cssMapFloor50000($value) {
		return $this->getFloorRange($value, 50000);
	}

	private function cssMapFloor100000($value) {
		return $this->getFloorRange($value, 100000);
	}

	private function getFloorRange($value, $steps) {
		$value = $this->cssMapFloorNumber($value);
		$multi = $value / $steps;
		$low = floor($multi);
		$high = $low + 1;

		$low = $low * $steps;
		$high = $high * $steps;

		if ($low == 0) {
			$low = 1;
		}

		$returnValue = sprintf('%s-%s', $low, $high);
		return $returnValue;
	}

	private function cssMapFloorNumber($value) {
		$LocaleInfo = localeconv();
		$value = str_replace($LocaleInfo["mon_thousands_sep"] , "", $value);
		$value = str_replace($LocaleInfo["mon_decimal_point"] , ".", $value);
		$value = floor($value);
		return $value;
	}
}
