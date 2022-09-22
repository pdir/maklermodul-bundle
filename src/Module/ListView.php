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

/**
 * Namespace.
 */

namespace Pdir\MaklermodulBundle\Module;

use Contao\BackendTemplate;
use Contao\File;
use Contao\FilesModel;
use Contao\FrontendTemplate;
use Contao\Module;
use Contao\PageModel;
use Contao\System;
use http\Exception\InvalidArgumentException;
use Pdir\MaklermodulBundle\Maklermodul\ContaoImpl\Domain\Model\IndexConfig;
use Pdir\MaklermodulBundle\Maklermodul\Domain\Model\IndexConfigInterface;
use Pdir\MaklermodulBundle\Maklermodul\Domain\Repository\EstateRepository;
use Pdir\MaklermodulBundle\Util\Helper;
use Symfony\Component\Routing\Exception\ExceptionInterface;

/**
 * Class ListView.
 *
 * @copyright  pdir / digital agentur
 * @author     Mathias Arzberger
 */
class ListView extends Module
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
     * @var EstateRepository
     */
    private $repository;

    /**
     * @var IndexConfigInterface
     */
    private $config;

    /**
     * @var PageModel
     */
    private $detailPage;

    public function __construct($objModule, $strColumn = 'main')
    {
        parent::__construct($objModule, $strColumn);

        if (!empty($this->arrData['immo_listTemplate']) && TL_MODE !== 'BE') {
            $this->strTemplate = $this->arrData['immo_listTemplate'];
        }

        /** @var PageModel $pageModel */
        $pageModel = PageModel::findPublishedByIdOrAlias($this->arrData['immo_readerPage']);

        if (null === $pageModel) {
            throw new InvalidArgumentException(sprintf('%s [ID %s]', $GLOBALS['TL_LANG']['MOD']['makler_modul_mplus']['error']['no_detail_page'], $objModule->id));
        }

        $this->detailPage = $pageModel->current();
    }

    /**
     * Display a wildcard in the back end.
     */
    public function generate(): string
    {
        if (TL_MODE === 'BE') {
            /** @var BackendTemplate|object $objTemplate */
            $objTemplate = new BackendTemplate('be_wildcard');

            $objTemplate->wildcard = '### '.$GLOBALS['TL_LANG']['FMD']['immoListView'][0].' ###';
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
        $container = System::getContainer();
        $strRootDir = $container->getParameter('kernel.project_dir').\DIRECTORY_SEPARATOR.$container->getParameter('contao.upload_path');

        return $strRootDir.\DIRECTORY_SEPARATOR.'maklermodul'.\DIRECTORY_SEPARATOR.'data'.\DIRECTORY_SEPARATOR;
    }

    /**
     * @throws \Exception
     */
    public function getListSourceUri($full = false): string
    {
        if (!method_exists($this->Template->config, 'getStorageFileUri')) {
            throw new \Exception($GLOBALS['TL_LANG']['MOD']['makler_modul_mplus']['error']['no_detail_page']);
        }

        if ($full) {
            $path = Helper::imagePath.$this->Template->config->getStorageFileUri();
        } else {
            $path = $this->Template->config->getStorageFileUri();
        }

        return $path;
    }

    /**
     * @throws ExceptionInterface
     */
    public function getDetailViewPrefix()
    {
        if ($this->Template->staticFilter) {
            return '';
        }

        $baseUri = $this->detailPage->getFrontendUrl();

        $urlSuffix = '.html';

        $container = System::getContainer();

        if ($container->hasParameter('contao.url_suffix')) {
            $urlSuffix = $container->getParameter('contao.url_suffix');
        }

        if ('' === $urlSuffix) {
            return str_replace($this->detailPage->alias, $this->detailPage->alias.'/'.DetailView::PARAMETER_KEY, $baseUri);
        }

        return preg_replace('%'.preg_quote($urlSuffix).'$%', '/'.DetailView::PARAMETER_KEY, $baseUri);
    }

    public function getObjects(): array
    {
        return $this->arrData;
    }

    public function formatValue($sVal): string
    {
        return number_format((float) $sVal, 2, ',', '.');
    }

    /**
     * Generate the module.
     *
     * @throws \Exception
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

        if ('1' === $this->arrData['immo_staticFilter']) {
            $this->Template->staticFilter = true;
            $this->Template->staticListPage = '/'.PageModel::findPublishedByIdOrAlias($this->arrData['immo_filterListPage'])->current()->getFrontendUrl();
        }

        $this->Template->config = new IndexConfig($this->arrData);

        // image params
        $arrImgSize = unserialize($this->imgSize);
        $this->Template->listImageType = 'image';
        $this->Template->listImageWidth = '300';
        $this->Template->listImageHeight = '200';
        $this->Template->listImageMode = 'crop';

        if ('' !== $arrImgSize[2]) {
            $this->Template->listImageWidth = $arrImgSize[0];
            $this->Template->listImageHeight = $arrImgSize[1];
            $this->Template->listImageSize = $arrImgSize[2];
            $this->Template->listImageType = 'picture';

            if (!is_numeric($arrImgSize[2])) {
                // image mode: proportional, crop or box
                $this->Template->listImageMode = $arrImgSize[2];
                $this->Template->listImageType = 'image';
            }
        }

        if (TL_MODE === 'FE' && '0' === $this->arrData['immo_staticFilter']) {
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

        $this->Template->placeholderImg = $this->makler_listViewPlaceholder ? FilesModel::findByUuid($this->makler_listViewPlaceholder)->path : Helper::assetFolder.'/img/platzhalterbild.jpg';

        //// get immo objects from xml file
        $pages = [];

        $objFile = new File($this->getListSourceUri(true));

        if (null === $objFile) {
            return $GLOBALS['TL_LANG']['MOD']['makler_modul_mplus']['error']['has-no-objects'];
        }

        // get data from json
        $json = json_decode($objFile->getContent(), true);

        if ($json && 0 === \count($json['data'])) {
            return $GLOBALS['TL_LANG']['MOD']['makler_modul_mplus']['error']['has-no-objects'];
        }

        if ($this->arrData['immo_listSort'] && '-' !== $this->arrData['immo_listSort']) {
            $json['data'] = $this->sortByKeyValue($json['data'], $this->arrData['immo_listSort'], 'true' === $this->arrData['makler_listSortAsc'] ? SORT_ASC : SORT_DESC);
        }

        $newEstates = [];
        $pageCount = 1;

        if ($this->makler_addListPagination && 'true' === $this->Input->get('estate-filter')) {
            foreach ($json['data'] as $estate) {
                foreach ($_REQUEST as $key => $value) {
                    if (false !== strpos($estate['css-filter-class-string'], $key.'-'.$value)) { // Yoshi version
                        $newEstates[$estate['uriident']] = $estate;
                    }
                }
            }
            $pages = array_chunk($newEstates, $this->makler_paginationCount);
        } elseif ($this->makler_addListPagination && $this->makler_paginationUseIsotope) {
            $count = 1;

            foreach ($json['data'] as $estate) {
                $estate['css-filter-class-string'] .= ' page'.$pageCount;
                $newEstates[] = $estate;

                if (0 !== $this->makler_paginationCount) {
                    if (0 === $count % $this->makler_paginationCount) {
                        ++$pageCount;
                    }
                }
                ++$count;
            }
            $pages[] = $newEstates;
        } elseif ($this->makler_addListPagination) {
            $pages = array_chunk($json['data'], $this->makler_paginationCount);
        } else {
            $pages[] = $json['data'];
        }

        $this->Template->pageCount = $pageCount;
        $this->Template->page = !(int) $this->Input->get('page') ? (int) $this->Input->get('page') : 0;
        $this->Template->listObjects = $json['data'] ? $pages : null;

        //// render filter template
        $strFilterTemplate = 'makler_list_filter_button';

        if ('select' === $this->makler_listFilterTemplate) {
            $strFilterTemplate = 'makler_list_filter_select';
            $this->Template->filterClass = 'select-filter';
        } else {
            $this->Template->filterClass = 'button-filter';
        }

        $objFilterTemplate = new FrontendTemplate($strFilterTemplate);

        // filter translation
        foreach ($json['filterConfig']['values'] as $key => $filter) {
            foreach ($filter as $filterValueKey => $filterValue) {
                $filterKey = str_replace('-', '.', $key);
                $name = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys'][$filterKey.'.@'.$filterValue['name']]
                    ?: $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys'][$filterKey.'.'.$filterValue['name']];

                if (null !== $name) {
                    $json['filterConfig']['values'][$key][$filterValueKey]['name'] = $name;
                }

                if (ctype_upper($filterValue['name'])) {
                    $str = ucfirst(strtolower($filterValue['name']));
                    $json['filterConfig']['values'][$key][$filterValueKey]['name'] = $str;
                }
            }
        }

        $objFilterTemplate->filterConfig = $json['filterConfig'];
        $this->Template->filter = $objFilterTemplate->parse();

        if ('1' === $this->arrData['immo_listDebug']) {
            $objFile = new File(Helper::imagePath.'/key-index.json');
            $keyIndex = json_decode($objFile->getContent(), true);
            natsort($keyIndex);
            $this->Template->debug = true;
            $this->Template->keyIndex = array_unique($keyIndex);
            $this->Template->debugObjectCount = \count($json['data']);
            $this->Template->debugObjects = $json['data'];
        }

        $this->Template->storageDirectoryPath = $this->getRootDir();
        $this->Template->staticFilter = $this->immo_staticFilter ? true : false;
        $this->Template->filterType = $this->makler_filter_type;

        // url suffix
        $this->Template->urlSuffix = '.html';

        $container = System::getContainer();

        if ($container->hasParameter('contao.url_suffix')) {
            $this->Template->urlSuffix = $container->getParameter('contao.url_suffix');
        }
    }

    private function isJsonString($str)
    {
        return null !== json_decode($str);
    }

    private function validateSettings(): void
    {
        if ('0' === $this->arrData['immo_staticFilter'] && '0' === $this->arrData['immo_readerPage']) {
            throw new Exception('Undefined reader page');
        }

        if ('1' === $this->arrData['immo_staticFilter'] && '0' === $this->arrData['immo_filterListPage']) {
            throw new Exception('Undefined filter list page');
        }
    }

    private function sortByKeyValue($data, $sortKey, $dir = SORT_ASC)
    {
        $sort_col = [];

        $sortKey = str_replace('.', '-', $sortKey);

        foreach ($data as $key => $row) {
            $sort_col[$key] = $row[$sortKey];
        }

        if (null !== $data) {
            array_multisort($sort_col, $dir, $data);
        }

        return $data;
    }
}
