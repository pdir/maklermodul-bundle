<?php

declare(strict_types=1);

/*
 * maklermodul bundle for Contao Open Source CMS
 *
 * Copyright (c) 2023 pdir / digital agentur // pdir GmbH
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

use Contao\Controller;
use Contao\Database;
use Contao\File;
use Contao\Frontend;
use Contao\FrontendTemplate;
use Contao\PageModel;
use Pdir\MaklermodulBundle\Maklermodul\ContaoImpl\Repository\IndexConfigRepository;
use Pdir\MaklermodulBundle\Maklermodul\Domain\Repository\EstateRepository;
use Pdir\MaklermodulBundle\Module\DetailView;
use Pdir\MaklermodulBundle\Module\ListPaginationView;

class Helper extends Frontend
{
    /**
     * maklermodul version.
     */
    const VERSION = '2.8.4';

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
     * @var array
     */
    const imagePath = ['files','maklermodul', 'data'];

    /**
     * API Url.
     *
     * @var string
     */
    public static $apiUrl = 'https://pdir.de/api/maklermodul/';

    public function getAds()
    {
        // only used for demo presentation
        $json = file_get_contents(self::$apiUrl.'list/all/'.Environment::get('server'));

        return json_decode($json, true); // load from local cache
    }

    public function getAdDetail($alias)
    {
        // only used for demo presentation
        $json = file_get_contents(self::$apiUrl.'ad/'.$alias.'/'.Environment::get('server'));

        return json_decode($json, true);
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

    public function addListPagination($objTemplate): void
    {
        if (false !== strpos(\get_class($objTemplate), 'FrontendTemplate')) {
            $objTemplate->hookAddListPagination = static function () use ($objTemplate) {
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
            if ($index->getStorageFileUri() && 1 === $index->getListInSitemap()) {
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
        $objFile = new File(self::imagePath.$fileNamePath);
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
        $pageModel = PageModel::findPublishedByIdOrAlias($id)->current()->row();
        $strUrl = Controller::generateFrontendUrl($pageModel, $vars); // example '/additionalquerystring/vars'

        return Environment::get('url').$websitePath.$strUrl;
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

        $database = Database::getInstance();
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

        if (\count($arrIndexFiles) < 1) {
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

    /**
     * Change form template for open immo feedback xml.
     *
     * @param $strBuffer
     * @param $strTemplate
     *
     * @return string
     */
    public function parseOpenImmoFeedbackTemplate($strBuffer, $strTemplate)
    {
        if ('form_xml' === $strTemplate) {
            if (Input::post('oobj_id')) {
                $repository = EstateRepository::getInstance();
                $objEstate = $repository->findByExternalObjectNumber(Input::post('oobj_id'));

                if (null === $objEstate) {
                    return $strBuffer;
                }

                $objTemplate = new FrontendTemplate('form_openimmo_feedback_xml');

                $objTemplate->name = 'makler modul für Contao v'.self::VERSION;
                $objTemplate->openimmo_anid = Input::findPost('openimmo_anid') ?: $objEstate->getValueOf('anbieter.openimmo_anid');
                $objTemplate->datum = $this->parseDate('d.m.Y', $this->timestamp);
                $objTemplate->makler_id = Input::findPost('anbieternr') ?: $objEstate->getValueOf('anbieter.anbieternr');

                $objTemplate->oobj_id = Input::findPost('openimmo_anid') ?: $objEstate->getValueOf('verwaltung_techn.openimmo_obid');
                $objTemplate->expose_url = $this->Environment->url.$this->Environment->requestUri;
                $objTemplate->zusatz_refnr = $objEstate->getValueOf('verwaltung_techn.objektnr_extern');
                $objTemplate->bezeichnung = $objEstate->getValueOf('freitexte.objekttitel');
                $objTemplate->strasse = $objEstate->getValueOf('geo.strasse');
                $objTemplate->ort = $objEstate->getValueOf('geo.ort');

                // customer fields
                $arrFields = [];

                if (Input::findPost('int_id')) {
                    $arrFields['int_id'] = Input::findPost('int_id');
                }

                if (Input::findPost('anrede')) {
                    $arrFields['anrede'] = Input::findPost('anrede');
                }

                if (Input::findPost('vorname')) {
                    $arrFields['vorname'] = Input::findPost('vorname');
                }

                if (Input::findPost('nachname')) {
                    $arrFields['nachname'] = Input::findPost('nachname');
                }

                if (Input::findPost('firma')) {
                    $arrFields['firma'] = Input::findPost('firma');
                }

                if (Input::findPost('strasse')) {
                    $arrFields['strasse'] = Input::findPost('strasse');
                }

                if (Input::findPost('postfach')) {
                    $arrFields['postfach'] = Input::findPost('postfach');
                }

                if (Input::findPost('plz')) {
                    $arrFields['plz'] = Input::findPost('plz');
                }

                if (Input::findPost('ort')) {
                    $arrFields['ort'] = Input::findPost('ort');
                }

                if (Input::findPost('tel')) {
                    $arrFields['tel'] = Input::findPost('tel');
                }

                if (Input::findPost('fax')) {
                    $arrFields['fax'] = Input::findPost('fax');
                }

                if (Input::findPost('mobil')) {
                    $arrFields['mobil'] = Input::findPost('mobil');
                }

                if (Input::findPost('email')) {
                    $arrFields['email'] = Input::findPost('email');
                }

                if (Input::findPost('bevorzugt')) {
                    $arrFields['bevorzugt'] = Input::findPost('bevorzugt');
                }

                if (Input::findPost('wunsch')) {
                    $arrFields['wunsch'] = Input::findPost('wunsch');
                }

                if (Input::findPost('anfrage')) {
                    $arrFields['anfrage'] = Input::findPost('anfrage');
                }

                $objTemplate->fields = $arrFields;
                $objTemplate->charset = Config::get('characterSet');

                return $objTemplate->parse();

                // openimmo_feedback.xml
            }
        }

        return $strBuffer;
    }

    /*
     * Workaround for \Contao\ArrayUtil in Contao 4.9
     */
    public static function arrayInsert(&$arrCurrent, $intIndex, $arrNew): void
    {
        if (!\is_array($arrCurrent)) {
            $arrCurrent = $arrNew;

            return;
        }

        if (\is_array($arrNew)) {
            $arrBuffer = array_splice($arrCurrent, 0, $intIndex);
            $arrCurrent = array_merge_recursive($arrBuffer, $arrNew, $arrCurrent);

            return;
        }

        array_splice($arrCurrent, $intIndex, 0, $arrNew);
    }

    public static function getImageLinkPath(): string
    {
        return join('/', self::imagePath) . '/';
    }

    public static function getImagePath(): string
    {
        return join(\DIRECTORY_SEPARATOR, self::imagePath) . \DIRECTORY_SEPARATOR;
    }
}
