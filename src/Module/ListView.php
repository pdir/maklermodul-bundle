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

namespace Pdir\MaklermodulBundle\Module;

use Contao\CoreBundle\Exception\PageNotFoundException;
use Patchwork\Utf8;
use Pdir\MaklermodulBundle\Maklermodul\ContaoImpl\Domain\Model\IndexConfig;
use Pdir\MaklermodulBundle\Maklermodul\Domain\Model\Estate;
use Pdir\MaklermodulBundle\Maklermodul\Domain\Repository\EstateRepository;
use Pdir\MaklermodulBundle\Util\Helper;

/**
 * Class ListView.
 *
 * @copyright  pdir / digital agentur
 * @author     Mathias Arzberger
 */
class ListView extends \Module
{
    const DEFAULT_TEMPLATE = 'makler_list';

    /**
     * @var array
     */
    public $objects;

    /**
     * @var string
     */
    public $assetFolder = 'bundles/pdirmaklermodul/';

    /**
     * Template.
     *
     * @var string
     */
    protected $strTemplate = self::DEFAULT_TEMPLATE;

    /**
     * @var \Pdir\MaklermodulBundle\Maklermodul\Domain\Repository\EstateRepository
     */
    private $repository;

    /**
     * @var \Pdir\MaklermodulBundle\Maklermodul\Domain\Model\IndexConfigInterface
     */
    private $config;

    /**
     * @var \PageModel
     */
    private $detailPage;

    public function __construct($objModule, $strColumn = 'main')
    {
        parent::__construct($objModule, $strColumn);

        if (!empty($this->arrData['immo_listTemplate']) and TL_MODE !== 'BE') {
            $this->strTemplate = $this->arrData['immo_listTemplate'];
        }

        $this->detailPage = \PageModel::findPublishedByIdOrAlias($this->arrData['immo_readerPage'])->current();
    }

    /**
     * Display a wildcard in the back end.
     *
     * @return string
     */
    public function generate()
    {
        if (TL_MODE === 'BE') {
            /** @var BackendTemplate|object $objTemplate */
            $objTemplate = new \BackendTemplate('be_wildcard');

            $objTemplate->wildcard = '### '.Utf8::strtoupper($GLOBALS['TL_LANG']['FMD']['immoListView'][0]).' ###';
            $objTemplate->title = $this->headline;
            $objTemplate->id = $this->id;
            $objTemplate->link = $this->name;
            $objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id='.$this->id;

            return $objTemplate->parse();
        }

        return parent::generate();
    }

    public function toUri($path)
    {
        return str_replace('/var/www/', '', $path);
    }

    public function getRootDir()
    {
        $container = \System::getContainer();
        $strRootDir = $container->getParameter('kernel.project_dir').\DIRECTORY_SEPARATOR.$container->getParameter('contao.upload_path');

        return $strRootDir.\DIRECTORY_SEPARATOR.'maklermodul'.\DIRECTORY_SEPARATOR.'data'.\DIRECTORY_SEPARATOR;
    }

    public function getListSourceUri($full = false)
    {
        if (!method_exists($this->Template->config, 'getStorageFileUri')) {
            echo $GLOBALS['TL_LANG']['MOD']['makler_modul_mplus']['error']['no_detail_page'];
            die();
        }
        if ($full) {
            $path = Helper::imagePath.$this->Template->config->getStorageFileUri();
        } else {
            $path = $this->Template->config->getStorageFileUri();
        }

        return $path;
    }

    public function getDetailViewPrefix()
    {
        if ($this->Template->staticFilter) {
            return '';
        }

        $baseUri = $this->detailPage->getFrontendUrl();

        $urlSuffix = '.html';

        $container = \System::getContainer();
        if ($container->hasParameter('contao.url_suffix')) {
            $urlSuffix = $container->getParameter('contao.url_suffix');
        }

        if($urlSuffix === '')
            return str_replace($this->detailPage->alias, $this->detailPage->alias . '/' . DetailView::PARAMETER_KEY, $baseUri);

        return str_replace($urlSuffix, '/'.DetailView::PARAMETER_KEY, $baseUri);
    }

    public function getObjects()
    {
        return $this->arrData;
    }

    public function formatValue($sVal)
    {
        return number_format((float) $sVal, 2, ',', '.');
    }

    /**
     * manipulate filter names.
     *
     * @param string $str
     *
     * @return string
     */
    public function setFilterName($str)
    {
        // $str = ucwords(strtolower($str));
        $str = str_replace('Miete/Pacht', 'Miete & Pacht', $str);

        $str = str_replace('grundstueck', 'Grundstück', $str);
        $str = str_replace('buero_praxen', 'Büros & Praxen', $str);
        $str = str_replace('hallen_lager_prod', 'Hallen, Lager & Produktion', $str);
        $str = str_replace('land_und_forstwirtschaft', 'Land- & Forstwirtschaft', $str);
        $str = str_replace('freizeitimmobilie_gewerblich', 'Freizeitimmobilie, gewerblich', $str);
        $str = str_replace('zinshaus_renditeobjekt', 'Grundstück', $str);

        $str = str_replace('LOFT-STUDIO-ATELIER', 'Loft, Studio & Atelier', $str);
        $str = str_replace('KEINE_ANGABE', 'Keine Angabe', $str);
        $str = str_replace('DOPPELHAUSHAELFTE', 'Doppelhaushälfte', $str);
        $str = str_replace('BERGHUETTE', 'Berghütte', $str);
        $str = str_replace('LAND_FORSTWIRSCHAFT', 'Land- & Forstwirtschaft', $str);
        $str = str_replace('BUEROFLAECHE', 'Bürofläche', $str);
        $str = str_replace('BUEROHAUS', 'Bürohaus', $str);
        $str = str_replace('BUEROZENTRUM', 'Bürozentrum', $str);
        $str = str_replace('LOFT_ATELIER', 'Loft & Atelier', $str);
        $str = str_replace('PRAXISFLAECHE', 'Praxisfläche', $str);
        $str = str_replace('AUSSTELLUNGSFLAECHE', 'Ausstellungsflächge', $str);
        $str = str_replace('SHARED_OFFICE', 'Shared Office', $str);
        $str = str_replace('FACTORY_OUTLET', 'Factory Outlet', $str);
        $str = str_replace('VERKAUFSFLAECHE', 'Verkauffläche', $str);
        $str = str_replace('GASTRONOMIE_UND_WOHNUNG', 'Gastronomie & Wohnung', $str);
        $str = str_replace('WEITERE_BEHERBERGUNGSBETRIEBE', 'Weitere Beherbergungsbetriebe', $str);
        $str = str_replace('LAGERFLAECHEN', 'Lagerflächen', $str);
        $str = str_replace('LAGER_MIT_FREIFLAECHE', 'Lager mit Freifläche', $str);
        $str = str_replace('FREIFLAECHEN', 'Freiflächen', $str);
        $str = str_replace('KUEHLHAUS', 'Kühlhaus', $str);
        $str = str_replace('LANDWIRTSCHAFTLICHE_BETRIEBE', 'Landwirtschaftliche Betriebe', $str);
        $str = str_replace('JAGD_UND_FORSTWIRTSCHAFT', 'Jagd- & Forstwirtschaft', $str);
        $str = str_replace('TEICH_UND_FISCHWIRTSCHAFT', 'Teich- & Fischwitschaft', $str);
        $str = str_replace('REITERHOEFE', 'Reiterhöfe', $str);
        $str = str_replace('SONSTIGE_LANDWIRTSCHAFTSIMMOBILIEN', 'Sonstige Landwitschaftsimmobilien', $str);
        $str = str_replace('PARKPLATZ_STROM', 'Parkplatz Strom', $str);
        $str = str_replace('VERGNUEGUNGSPARKS_UND_CENTER', 'Vergnügungsparks & -center', $str);
        $str = str_replace('WOHN_UND_GESCHAEFTSHAUS', 'Wohn- & Geschäftshaus', $str);
        $str = str_replace('GESCHAEFTSHAUS', 'Geschäftshaus', $str);
        $str = str_replace('BUEROGEBAEUDE', 'Bürogebäude', $str);
        $str = str_replace('SB_MAERKTE', 'SB Märkte', $str);
        $str = str_replace('VERBRAUCHERMAERKTE', 'Verbrauchermärkte', $str);
        $str = str_replace('BETREUTES-WOHNEN', 'Betreutes Wohnen', $str);
        if (ctype_upper($str)) {
            $str = ucfirst(strtolower($str));
        }

        return $str;
    }

    /**
     * Generate the module.
     */
    protected function compile()
    {
        $this->validateSettings();

        $this->Template->helper = $this;

        if ($this->makler_useModuleJs) {
            $GLOBALS['TL_JAVASCRIPT']['jquery.template'] = $this->assetFolder.'js/jquery.loadTemplate.min.js|static';
            $GLOBALS['TL_JAVASCRIPT']['mixitup'] = $this->assetFolder.'js/jquery.isotope.min.js|static';
            $GLOBALS['TL_JAVASCRIPT']['browser'] = $this->assetFolder.'js/jquery.browser.min.js|static';
            $GLOBALS['TL_JAVASCRIPT']['estate'] = $this->assetFolder.'js/estate.js|static';
        }
        if ($this->makler_useModuleCss) {
            $GLOBALS['TL_CSS']['estate'] = $this->assetFolder.'css/estate.scss||static';
        }

        if (1 === $this->arrData['immo_staticFilter']) {
            $this->Template->staticFilter = true;
            $this->Template->staticListPage = '/'.\PageModel::findPublishedByIdOrAlias($this->arrData['immo_filterListPage'])->current()->getFrontendUrl();
        }

        $this->Template->config = new IndexConfig($this->arrData);

        // image params
        $arrImgSize = unserialize($this->imgSize);
        if (count($arrImgSize) < 1) {
            $this->Template->listImageWidth = '293';
            $this->Template->listImageHeight = '220';
            $this->Template->listImageMode = 'center_center';
        } else {
            $this->Template->listImageWidth = $arrImgSize[0];
            $this->Template->listImageHeight = $arrImgSize[1];
            $this->Template->listImageMode = $arrImgSize[2];
        }

        if (TL_MODE === 'FE' && 0 === $this->arrData['immo_staticFilter']) {
            // @ToDo Fehler bei deaktivierter Detailseite beheben (Fatal error: Call to a member function current() on a non-object in /.../system/modules/makler_modul_mplus/modules/ListView.php on line 105
            $this->repository = EstateRepository::getInstance();
            $this->Template->objectData = $this->repository->findAll();
        }

        if ($this->isJsonString($this->arrData['immo_listSort'])) {
            $this->Template->sorting = $this->arrData['immo_listSort'];
        } elseif ('-' !== $this->arrData['immo_listSort']) {
            $this->Template->sorting = "{sortBy: '".$this->arrData['immo_listSort']."',sortAscending: ".$this->arrData['makler_listSortAsc'].",sortType: '".$this->arrData['makler_listSortType']."'}";
        } else {
            // set default listing mode
            $this->Template->sorting = "{sortBy: 'anbieter.openimmo_anid',sortAscending: false,sortType: 'int'}";
        }

        //// params
        $this->Template->filterTemplate = $this->makler_listFilterTemplate;
        $this->Template->paginationUseIsotope = $this->makler_paginationUseIsotope;
        $this->Template->addListPagination = $this->makler_addListPagination ? true : false;

        $this->Template->formatValue = function ($strVal) {
            return $this->formatValue($strVal);
        };

        $this->Template->placeholderImg = $this->makler_listViewPlaceholder ? \FilesModel::findByUuid($this->makler_listViewPlaceholder)->path : Helper::assetFolder.'/img/platzhalterbild.jpg';

        //// get immo objects from xml file
        $pages = [];

        $objFile = new \File($this->getListSourceUri(true));
        $json = json_decode($objFile->getContent(), true);

        if (0 === count($json)) {
            throw new PageNotFoundException('Page not found: '.\Environment::get('uri'));
        }

        $newEstates = [];
        $pageCount = 1;
        if ($this->makler_addListPagination && 'true' === $this->Input->get('estate-filter')) {
            foreach ($json['data'] as $estate):
                foreach ($_REQUEST as $key => $value) {
                    if (false !== strpos($estate['css-filter-class-string'], $key.'-'.$value)) { // Yoshi version
                        $newEstates[$estate['uriident']] = $estate;
                    }
                }
            endforeach;
            $pages = array_chunk($newEstates, $this->makler_paginationCount);
        } elseif ($this->makler_addListPagination && $this->makler_paginationUseIsotope) {
            $count = 1;
            foreach ($json['data'] as $estate):
                $estate['css-filter-class-string'] = $estate['css-filter-class-string'].' page'.$pageCount;
            $newEstates[] = $estate;
            if (0 !== $this->makler_paginationCount) {
                if (0 === ($count % $this->makler_paginationCount)) {
                    ++$pageCount;
                }
            }
            ++$count;
            endforeach;
            $pages[] = $newEstates;
        } elseif ($this->makler_addListPagination) {
            $pages = array_chunk($json['data'], $this->makler_paginationCount);
        } else {
            $pages[] = $json['data'];
        }

        $this->Template->pageCount = 1 === $pageCount ? $pageCount : $pageCount - 1;
        $this->Template->page = !(int) $this->Input->get('page') ? (int) $this->Input->get('page') : 0;
        $this->Template->listObjects = count($json['data']) > 0 ? $pages : null;

        //// render filter template
        $strFilterTemplate = 'makler_list_filter_button';
        if ('select' === $this->makler_listFilterTemplate) {
            $strFilterTemplate = 'makler_list_filter_select';
            $this->Template->filterClass = 'select-filter';
        } else {
            $this->Template->filterClass = 'button-filter';
        }

        $objFilterTemplate = new \FrontendTemplate($strFilterTemplate);
        $objFilterTemplate->filterConfig = $json['filterConfig'];
        $this->Template->filter = $objFilterTemplate->parse();

        if (1 === $this->arrData['immo_listDebug']) {
            $objFile = new \File(Helper::imagePath.'/key-index.json');
            $keyIndex = json_decode($objFile->getContent(), true);
            natsort($keyIndex);
            $this->Template->debug = true;
            $this->Template->keyIndex = array_unique($keyIndex);
            $this->Template->debugObjectCount = count($json['data']);
            $this->Template->debugObjects = $json['data'];
        }

        $this->Template->storageDirectoryPath = $this->getRootDir();
        $this->Template->staticFilter = $this->immo_staticFilter ? true : false;
        $this->Template->filterType = $this->makler_filter_type;

        // url suffix
        $this->Template->urlSuffix = '.html';

        $container = \System::getContainer();
        if ($container->hasParameter('contao.url_suffix')) {
            $this->Template->urlSuffix = $container->getParameter('contao.url_suffix');
        }
    }

    private function isJsonString($str)
    {
        return null !== json_decode($str);
    }

    private function validateSettings()
    {
        if (0 === $this->arrData['immo_staticFilter'] && 0 === $this->arrData['immo_readerPage']) {
            throw new \Exception('Undefined reader page');
        }

        if (1 === $this->arrData['immo_staticFilter'] && 0 === $this->arrData['immo_filterListPage']) {
            throw new \Exception('Undefined filter list page');
        }
    }
}
