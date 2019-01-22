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

namespace Pdir\MaklermodulBundle\Maklermodul\ContaoImpl;

class StaticDIC
{
    const CONFIG_ROOT = '../../Resources/contao/config';
    const FILTER_CSS_MAPPING = 'filter.ini';
    const USER_FILTER_CSS_MAPPING = '/files/maklermodul/data.filter.ini';

    private static $filterConfig = null;

    public static function getTranslationMap($useCore = true)
    {
        if (!isset($GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys']['language_loaded'])) {
            if ($useCore) {
                \System::loadLanguageFile('makler_modul_mplus');
            } else {
                // could not detect language in index processing so we could not use
                // contao's build in feature: System::loadLanguageFile('makler_modul_mplus');
                require_once __DIR__.'/'.self::CONFIG_ROOT.'/../languages/de/makler_modul_mplus.php';
                // include Local configuration file
                if (file_exists(TL_ROOT.'/system/config/langconfig.php')) {
                    require_once TL_ROOT.'/system/config/langconfig.php';
                }
            }
        }

        return $GLOBALS['TL_LANG']['makler_modul_mplus'];
    }

    public static function getCssFilterClassMapping()
    {
        if (null === self::$filterConfig) {
            $fileName = sprintf('%s/%s/%s',
                __DIR__,
                self::CONFIG_ROOT,
                self::FILTER_CSS_MAPPING
            );

            self::$filterConfig = parse_ini_file($fileName);
            // load user filter css mapping
            if (file_exists(\Environment::get('documentRoot').self::USER_FILTER_CSS_MAPPING)) {
                $userFilterConf = parse_ini_file(\Environment::get('documentRoot').self::USER_FILTER_CSS_MAPPING);
                self::$filterConfig = array_merge(self::$filterConfig, $userFilterConf);
            }
            if (false === self::$filterConfig) {
                throw new \Exception("Could not load filter mapping from $fileName");
            }
        }

        return self::$filterConfig;
    }

    /**
     * @param $fileName
     *
     * @return \Contao\File
     */
    public static function getFileHelper($fileName)
    {
        return new FileHelper($fileName, false);
    }

    /**
     * @param $folderName
     *
     * @return \Contao\Folder
     */
    public static function getFolderHelper($folderName)
    {
        return new FileHelper($folderName, true);
    }
}
