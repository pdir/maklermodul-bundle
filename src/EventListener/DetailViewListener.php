<?php

declare(strict_types=1);

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
namespace Pdir\MaklermodulBundle\EventListener;

use Contao\Config;
use Contao\CoreBundle\Framework\ContaoFrameworkInterface;
use Contao\FaqCategoryModel;
use Contao\FaqModel;
use Contao\PageModel;
use Contao\StringUtil;

class DetailViewListener
{
    const PARAMETER_KEY = 'expose';

    /**
     * @param array $arrFragments all url parameters exploded by /
     * @return array
     *
     * @see http://de.contaowiki.org/Strukturierte_URLs
     */
    public static function hookGetPageIdFromUrl($arrFragments)
    {
        if (!$_GET['objectId']) {
            $parameterKeyFound = false;
            foreach ($arrFragments as $key => $value) {
                if ($parameterKeyFound AND $value != 'auto_item') {
                    $_GET['objectId'] = $value;
                    break;
                }

                if ($value == self::PARAMETER_KEY) {
                    $parameterKeyFound = true;
                }
            }

            if ($parameterKeyFound) {
                return array($arrFragments[0]);
            }
        }

        return $arrFragments;
    }
}
