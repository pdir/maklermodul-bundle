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
namespace Pdir\MaklermodulBundle\Util;


class Helper extends \Frontend
{
    /**
     * mobilede version
     */
    const VERSION = '2.0.0';

    /**
     * Extension mode
     * @var boolean
     */
    const MODE = 'DEMO';

    /**
     * API Url
     * @var string
     */
    static $apiUrl = 'https://pdir.de/api/maklermodul/';

    /**
     * Path to Asset Folder
     * @var string
     */
    const assetFolder = 'bundles/pdirmaklermodul';

    /**
     * Path to Image
     * @var string
     */
    const imagePath = '/files/maklermodul/data/';

    public function getAds()
    {
        // only used for demo presentation
        $json = file_get_contents(self::$apiUrl . 'list/all/' . \Environment::get('server'));
        $arrAds = json_decode( $json, true );
        return $arrAds; // load from local cache
    }

    public function getAdDetail($alias)
    {
        // only used for demo presentation
        $json = file_get_contents(self::$apiUrl . 'ad/' . $alias . '/' . \Environment::get('server'));
        $arrAd = json_decode( $json, true );
        return $arrAd;
    }
}
