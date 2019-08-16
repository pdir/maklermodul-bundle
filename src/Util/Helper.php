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

namespace Pdir\MaklermodulBundle\Util;

use Pdir\MaklermodulBundle\Maklermodul\ContaoImpl\Repository\IndexConfigRepository;
use Pdir\MaklermodulBundle\Module\DetailView;
use Pdir\MaklermodulBundle\Module\ListPaginationView;

class Helper extends \Frontend
{
    /**
     * maklermodul version.
     */
    const VERSION = '2.2.0';

    /**
     * Extension mode.
     *
     * @var bool
     */
    const MODE = 'DEMO';

    /**
     * Path to Asset Folder.
     *
     * @var string
     */
    const assetFolder = 'bundles/pdirmaklermodul';

    /**
     * Path to Image.
     *
     * @var string
     */
    const imagePath = 'files/maklermodul/data/';

    /**
     * API Url.
     *
     * @var string
     */
    public static $apiUrl = 'https://pdir.de/api/maklermodul/';

    public function getAds()
    {
        // only used for demo presentation
        $json = file_get_contents(self::$apiUrl.'list/all/'.\Environment::get('server'));
        $arrAds = json_decode($json, true);

        return $arrAds; // load from local cache
    }

    public function getAdDetail($alias)
    {
        // only used for demo presentation
        $json = file_get_contents(self::$apiUrl.'ad/'.$alias.'/'.\Environment::get('server'));
        $arrAd = json_decode($json, true);

        return $arrAd;
    }

    public function addPrivacyWidget($arrWidgets)
    {
        $arrWidgets[] = [
            'title' => 'pdir/maklermodul-bundle',
            'content' => $GLOBALS['TL_LANG']['MOD']['maklermodulPrivacy'],
            'class' => 'orange icon',
        ];

        return $arrWidgets;
    }

    public function addListPagination($objTemplate)
    {
        if (false !== strpos(get_class($objTemplate), 'FrontendTemplate')) {
            $objTemplate->hookAddListPagination = function () use ($objTemplate) {
                if ($objTemplate->addListPagination) {
                    $objListPaginationView = new ListPaginationView($objTemplate);

                    return $objListPaginationView->generate();
                }

                return '';
            };
        }
    }

    /**
     * @param array $arrPages
     * @param array $intRoot
     *
     * @return array
     */
    public function addProductsToSearchIndex($arrPages)
    {
        $indexConfigRepository = new IndexConfigRepository();
        $indexObjects = $indexConfigRepository->findAll();

        $newEstatePages = [];
        foreach ($indexObjects as $index) {
            if ($index->getStorageFileUri() and 1 === $index->getListInSitemap()) {
                $allEstates = $this->loadJsonFile($index->getStorageFileUri());

                foreach ($allEstates['data'] as $estate) {
                    if ($index->getReaderPageId()) {
                        $newEstatePages[] = $this->getPageUrl($index->getReaderPageId(), \DIRECTORY_SEPARATOR.DetailView::PARAMETER_KEY.\DIRECTORY_SEPARATOR.$estate['uriident']);
                    }
                }
            }
        }

        $newEstatePages = array_unique($newEstatePages);

        return array_merge($arrPages, $newEstatePages);
    }

    public static function loadJsonFile($fileNamePath)
    {
        $objFile = new \File(self::imagePath.$fileNamePath);
        $decoded = json_decode($objFile->getContent(), true);

        if (null === $decoded) {
            return null;
        }

        return $decoded;
    }

    public static function getPageUrl($id, $vars = '')
    {
        $websitePath = \DIRECTORY_SEPARATOR;
        if ($GLOBALS['TL_CONFIG']['websitePath']) {
            $websitePath = $GLOBALS['TL_CONFIG']['websitePath'].\DIRECTORY_SEPARATOR;
        }
        $pageModel = \PageModel::findPublishedByIdOrAlias($id)->current()->row();
        $strUrl = \Controller::generateFrontendUrl($pageModel, $vars); // example '/additionalquerystring/vars'
        return \Environment::get('url').$websitePath.$strUrl;
    }

    /**
     * Get the subpages for MaklerModulMplus detail page.
     *
     * @param array $item
     *
     * @return array|bool
     */
    public static function getSubPages($item)
    {
        $arrIndexFiles = [];
        $arrItems = [];

        $database = \Database::getInstance();
        $artResult = $database->query("SELECT `id` FROM `tl_article` WHERE `pid` = '".$item['id']."'");

        while ($artResult->next()) {
            $row = $artResult->row();
            $conResult = $database->query("SELECT `id`, `module` FROM `tl_content` WHERE `pid` = '".$row['id']."' AND `type` = 'module'");
            while ($conResult->next()) {
                $row = $conResult->row();
                $modResult = $database->query("SELECT `immo_actIndexFile`,`immo_readerPage` FROM `tl_module` WHERE `id` = '".$row['module']."' AND `type` = 'immoListView' AND `immo_listInSitemap` = 1");
                while ($modResult->next()) {
                    $row = $modResult->row();
                    if ($row['immo_actIndexFile']) {
                        $arrIndexFiles[] = $row;
                    }
                }
            }
        }

        if (count($arrIndexFiles) < 1) {
            return '';
        }

        foreach ($arrIndexFiles as $index) {
            $allEstates = self::loadJsonFile($index['immo_actIndexFile']);
            foreach ($allEstates['data'] as $estate) {
                $arrItems[] = [
                    'title' => $estate['freitexte-objekttitel'],
                    'href' => self::getPageUrl($index['immo_readerPage'], $GLOBALS['TL_CONFIG']['websitePath'].\DIRECTORY_SEPARATOR.DetailView::PARAMETER_KEY.\DIRECTORY_SEPARATOR.$estate['uriident']),
                    'alias' => $estate['uriident'],
                    'type' => 'regular',
                    'redirect' => 'permanent',
                    'pageTitle' => $estate['freitexte-objekttitel'],
                    'link' => $estate['freitexte-objekttitel'],
                    'class' => 'level_2',
                ];
            }
        }

        return $arrItems;
    }
}
