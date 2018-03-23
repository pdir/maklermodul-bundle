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

namespace Pdir\MaklermodulBundle\Maklermodul;

/**
 * maklermodul Runonce
 *
 * @author Mathias Arzberger <develop@pdir.de>
 */

class Runonce
{
    /**
     * module folder
     * @var string
     */
    const strFolder = 'files/maklermodul/';

    /**
     * Data folder
     * @var string
     */
    static $strDataFolder = self::strFolder . 'data';

    /**
     * Import folder
     * @var string
     */
    static $strImportFolder = self::strFolder . 'upload';

    /**
     * Org folder
     * @var string
     */
    static $strOrgFolder = self::strFolder . 'org';

    /**
     * Run file migrations
     *
     * @return void
     */
    public function run()
    {
        if(!file_exists(self::strFolder)) {
            new \Folder(self::strFolder);
        }

        if(!file_exists(self::$strDataFolder)) {
            new \Folder(self::$strDataFolder);
        }

        if(!file_exists(self::$strImportFolder)) {
            new \Folder(self::$strImportFolder);
        }

        if(!file_exists(self::$strOrgFolder)) {
            new \Folder(self::$strOrgFolder);
        }

        if(!file_exists(self::$strDataFolder . DIRECTORY_SEPARATOR . '.public')) {
            new \File(self::$strDataFolder . DIRECTORY_SEPARATOR . '.public');
        }
    }
}

$objRunonce = new Runonce();
$objRunonce->run();
