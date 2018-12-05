<?php

/*
 * maklermodul bundle for Contao Open Source CMS
 *
 * Copyright (c) 2018 pdir / digital agentur // pdir GmbH
 *
 * @package    maklermodul-bundle
 * @link       https://www.maklermodul.de
 * @license    proprietary / pdir license - All-rights-reserved - commercial extension
 * @author     Mathias Arzberger <develop@pdir.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pdir\MaklermodulBundle\Maklermodul;

/**
 * maklermodul Runonce.
 *
 * @author Mathias Arzberger <develop@pdir.de>
 */
class Runonce
{
    /**
     * module folder.
     *
     * @var string
     */
    const strFolder = 'files/maklermodul/';

    /**
     * Data folder.
     *
     * @var string
     */
    public static $strDataFolder = self::strFolder.'data';

    /**
     * Import folder.
     *
     * @var string
     */
    public static $strImportFolder = self::strFolder.'upload';

    /**
     * Org folder.
     *
     * @var string
     */
    public static $strOrgFolder = self::strFolder.'org';

    /**
     * Run file migrations.
     */
    public function run()
    {
        if (!file_exists(self::strFolder)) {
            new \Folder(self::strFolder);
        }

        if (!file_exists(self::$strDataFolder)) {
            new \Folder(self::$strDataFolder);
        }

        if (!file_exists(self::$strImportFolder)) {
            new \Folder(self::$strImportFolder);
        }

        if (!file_exists(self::$strOrgFolder)) {
            new \Folder(self::$strOrgFolder);
        }

        if (!file_exists(self::strFolder.'.public')) {
            \File::putContent(self::strFolder.'.public', '');
        }
    }
}

$objRunonce = new Runonce();
$objRunonce->run();
