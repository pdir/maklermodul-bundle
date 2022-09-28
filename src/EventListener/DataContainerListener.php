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

namespace Pdir\MaklermodulBundle\EventListener;

use Contao\BackendTemplate;
use Contao\Config;
use Contao\CoreBundle\ServiceAnnotation\Callback;
use Contao\DataContainer;
use Contao\Date;
use Contao\System;
use Pdir\MaklermodulBundle\Maklermodul\ContaoImpl\StaticDIC;
use Pdir\MaklermodulBundle\Model\MaklerModel;
use Pdir\MaklermodulBundle\Util\Helper;

class DataContainerListener
{
    /**
     * @Callback(table="tl_makler", target="list.label.label")
     */
    public function onLabelCallback(array $row): string
    {
        return sprintf(
            '<span style="color: #999;">[%s]</span> %s<span style="color: #999; margin-left: .5em;">⭳ %s</span>'.
            '<span style="color: #589b0e; margin-left: .5em;">↦ %s</span>',
            $row['extern'],
            $row['name'],
            Date::parse(Config::get('datimFormat'), $row['tstamp'] ?: '-'),
            Date::parse(Config::get('datimFormat'), $row['lastUpdate'])
        );
    }

    /**
     * @Callback(table="tl_makler", target="list.global_operations.toolbar.button", priority=1)
     */
    public function renderToolbar(): string
    {
        $template = new BackendTemplate('be_makler_toolbar');

        System::loadLanguageFile('modules');

        $template->strBundleName = $GLOBALS['TL_LANG']['MOD']['maklermodul'][0];
        $template->strBundleGreeting = sprintf($GLOBALS['TL_LANG']['MOD']['maklermodul']['greeting'], $template->strBundleName);
        $template->version = Helper::VERSION;
        $template->arrButtons = $GLOBALS['TL_LANG']['MOD']['maklermodul']['buttons'];
        $template->arrLinks = $GLOBALS['TL_LANG']['MOD']['maklermodul']['setupLinks'];

        $template->arrEditions = [
            'free' => [
                'price' => 0,
                //'product_link' => 'https://extensions.contao.org/?q=pdir&pages=1&p=pdir%2Fbusiness-reviews-bundle',
            ],
            'openImmoSync' => [
                'price' => 399,
                'product_link' => 'https://www.maklermodul.de/',
            ],
            'immoscoutSync' => [
                'price' => 399,
                'product_link' => 'https://www.maklermodul.de/',
            ],
        ];

        $template->arrLANG = $GLOBALS['TL_LANG']['MOD']['maklermodul'];

        return $template->parse();
    }

    /**
     * @Callback(table="tl_makler", target="config.ondelete", priority=1)
     */
    public function deleteObject(DataContainer $dc): void
    {
        if (!$dc->id) {
            return;
        }

        $this->deleteObjectFiles($dc->activeRecord->alias);
    }

    /**
     * @Callback(table="tl_makler", target="config.onsubmit", priority=1)
     */
    public function saveObject(DataContainer $dc): void
    {
        if (!$dc->activeRecord) {
            return;
        }

        // update item
        /** @var MaklerModel $maklerModel */
        $maklerModel = MaklerModel::findByPk($dc->activeRecord->id);
        $maklerModel->lastUpdate = time();
        $maklerModel->save();
    }

    private function deleteObjectFiles($identifier): void
    {
        $objectList = glob(Config::get('uploadPath').'/maklermodul/data/'.$identifier);

        if (!empty($objectList)) {
            foreach ($objectList as $object) {
                $deleteFilePath = sprintf($object.'.json');

                if (file_exists($deleteFilePath)) {
                    StaticDIC::getFileHelper($deleteFilePath)->delete();
                    $delteFolderPath = sprintf($object);
                    $this->deleteDir($delteFolderPath);
                }
            }
        }
    }

    private function deleteDir($dirPath)
    {
        if (!is_dir($dirPath)) {
            throw new \InvalidArgumentException("$dirPath must be a directory");
        }

        if ('/' !== substr($dirPath, \strlen($dirPath) - 1, 1)) {
            $dirPath .= '/';
        }

        if ($this->keepImageFolders) { // keep image folders
            $files = glob($dirPath.'*.json', GLOB_MARK);
        } else { // full cleanup
            $files = glob($dirPath.'*', GLOB_MARK);
        }

        foreach ($files as $file) {
            if (is_dir($file)) {
                $this->deleteDir($file);
            } else {
                StaticDIC::getFileHelper($file)->delete();
            }
        }
        StaticDIC::getFolderHelper($dirPath)->delete();

        return true;
    }
}
