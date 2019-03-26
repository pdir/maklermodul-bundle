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
 * Add palettes to tl_module.
 */
$GLOBALS['TL_DCA']['tl_module']['palettes']['immoListView']
    = '{title_legend},name,headline,type;'
    .'{template_legend},immo_readerPage,immo_listTemplate;'
    .'{field_legend},immo_listContent,immo_listFilter,makler_listFilterTemplate,makler_filter_type;'
    .'{makler_sort_legend},immo_listSort,makler_listSortType,makler_listSortAsc;'
    .'{makler_cond_legend},immo_listCondition;'
    .'{image_legend},imgSize,makler_listViewPlaceholder;'
    .'{makler_pagination_legend},makler_addListPagination;'
    .'{option_legend},immo_staticFilter,immo_filterListPage,immo_listInSitemap,immo_listDebug,makler_useModuleCss,makler_useModuleJs,makler_compatibilityMode';

$GLOBALS['TL_DCA']['tl_module']['palettes']['immoDetailView']
    = '{title_legend},name,headline,type;'
    .'{template_legend},immo_listPage,immo_readerTemplate,makler_showMap,makler_detailViewPlaceholder;'
    .'{image_legend},imgSize;'
    .'{option_legend},makler_useModuleDetailCss,makler_debug';

$GLOBALS['TL_DCA']['tl_module']['palettes']['immoHeaderImageView']
    = '{title_legend},name,headline,type,makler_showHeadline,makler_showBackgroundImage,makler_headerImageSource,makler_headerImagePlaceholder;'
    .'{expert_legend:hide},guests,cssID,space;';

$GLOBALS['TL_DCA']['tl_module']['palettes']['__selector__'][] = 'makler_addListPagination';
$GLOBALS['TL_DCA']['tl_module']['subpalettes']['makler_addListPagination'] = 'makler_paginationCount,makler_paginationLinkCount,makler_paginationUseIsotope'; // ,makler_paginationShowtitle';

/*
 * Add fields to tl_module
 */

$GLOBALS['TL_DCA']['tl_module']['fields']['immo_actIndexFile'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_module']['immo_actIndexFile'],
    'exclude' => true,
    'inputType' => 'text',
    'sql' => "varchar(255) NOT NULL default '00index-demo-00001.json'",
];

$GLOBALS['TL_DCA']['tl_module']['fields']['immo_readerTemplate'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_module']['immo_readerTemplate'],
    'exclude' => true,
    'inputType' => 'select',
    'options_callback' => ['tl_module_makler', 'getModuleTemplates'],
    'reference' => &$GLOBALS['TL_LANG']['tl_module'],
    'eval' => ['includeBlankOption' => true, 'tl_class' => 'w50'],
    'sql' => "varchar(32) NOT NULL default '0'",
];

$GLOBALS['TL_DCA']['tl_module']['fields']['immo_readerPage'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_module']['immo_readerPage'],
    'exclude' => true,
    'inputType' => 'pageTree',
    'reference' => &$GLOBALS['TL_LANG']['tl_module'],
    'eval' => ['includeBlankOption' => true, 'tl_class' => 'w50'],
    'sql' => "int(10) unsigned NOT NULL default '0'",
];

$GLOBALS['TL_DCA']['tl_module']['fields']['immo_listTemplate'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_module']['immo_listTemplate'],
    'exclude' => true,
    'inputType' => 'select',
    'options_callback' => ['tl_module_makler', 'getModuleTemplates'],
    'reference' => &$GLOBALS['TL_LANG']['tl_module'],
    'eval' => ['includeBlankOption' => true, 'tl_class' => 'w50'],
    'sql' => "varchar(32) NOT NULL default '0'",
];

$GLOBALS['TL_DCA']['tl_module']['fields']['immo_listContent'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_module']['immo_listContent'],
    'exclude' => true,
    'inputType' => 'textarea',
    'reference' => &$GLOBALS['TL_LANG']['tl_module'],
   // 'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50'),
    'sql' => 'text NULL',
];

$GLOBALS['TL_DCA']['tl_module']['fields']['immo_listPage'] = [
        'label' => &$GLOBALS['TL_LANG']['tl_module']['immo_listPage'],
        'exclude' => true,
        'inputType' => 'pageTree',
        'reference' => &$GLOBALS['TL_LANG']['tl_module'],
        'eval' => ['includeBlankOption' => true, 'tl_class' => 'w50 autoheight'],
        'sql' => "int(10) unsigned NOT NULL default '0'",
];

$GLOBALS['TL_DCA']['tl_module']['fields']['immo_filterListPage'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_module']['immo_filterListPage'],
    'exclude' => true,
    'inputType' => 'pageTree',
    'reference' => &$GLOBALS['TL_LANG']['tl_module'],
    'eval' => ['includeBlankOption' => true, 'tl_class' => 'w50 autoheight'],
    'sql' => "int(10) unsigned NOT NULL default '0'",
];

$GLOBALS['TL_DCA']['tl_module']['fields']['immo_listFilter'] = [
        'label' => &$GLOBALS['TL_LANG']['tl_module']['immo_listFilter'],
        'exclude' => true,
        'inputType' => 'textarea',
        'reference' => &$GLOBALS['TL_LANG']['tl_module'],
        // 'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50'),
        'sql' => 'text NULL',
];

$GLOBALS['TL_DCA']['tl_module']['fields']['makler_listFilterTemplate'] = [
        'label' => &$GLOBALS['TL_LANG']['tl_module']['makler_listFilterTemplate'],
        'inputType' => 'select',
        'default' => 'button',
        'options' => ['select', 'button'],
        'reference' => &$GLOBALS['TL_LANG']['tl_module']['makler_listFilterTemplateOptions'],
        'eval' => ['tl_class' => 'w50'],
        'sql' => "varchar(255) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_module']['fields']['immo_listSort'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_module']['immo_listSort'],
    'inputType' => 'select',
    'default' => '',
    'options_callback' => ['tl_module_makler', 'getFieldKeys'],
    'reference' => &$GLOBALS['TL_LANG']['tl_module'],
    'eval' => ['tl_class' => 'w50'],
    'sql' => "varchar(255) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_module']['fields']['makler_listSortAsc'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_module']['makler_listSortAsc'],
    'inputType' => 'select',
    'default' => '',
    'options' => ['true', 'false'],
    'reference' => &$GLOBALS['TL_LANG']['tl_module']['makler_listSortAscOptions'],
    'eval' => ['tl_class' => 'w50'],
    'sql' => "varchar(255) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_module']['fields']['makler_listSortType'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_module']['makler_listSortType'],
    'inputType' => 'select',
    'default' => '',
    'options' => ['int', 'float', 'text'],
    'reference' => &$GLOBALS['TL_LANG']['tl_module']['makler_listSortTypeOptions'],
    'eval' => ['tl_class' => 'w50'],
    'sql' => "varchar(255) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_module']['fields']['immo_listCondition'] = [
        'label' => &$GLOBALS['TL_LANG']['tl_module']['immo_listCondition'],
        'exclude' => true,
        'inputType' => 'textarea',
        'reference' => &$GLOBALS['TL_LANG']['tl_module'],
        'sql' => 'text NULL',
];

$GLOBALS['TL_DCA']['tl_module']['fields']['makler_listViewPlaceholder'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_module']['makler_listViewPlaceholder'],
    'exclude' => true,
    'inputType' => 'fileTree',
    'reference' => &$GLOBALS['TL_LANG']['tl_module'],
    'eval' => ['fieldType' => 'radio', 'filesOnly' => true, 'tl_class' => 'clr', 'extensions' => $GLOBALS['TL_CONFIG']['validImageTypes']],
    'sql' => 'binary(16) NULL',
];

$GLOBALS['TL_DCA']['tl_module']['fields']['makler_detailViewPlaceholder'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_module']['makler_detailViewPlaceholder'],
    'exclude' => true,
    'inputType' => 'fileTree',
    'reference' => &$GLOBALS['TL_LANG']['tl_module'],
    'eval' => ['fieldType' => 'radio', 'filesOnly' => true, 'tl_class' => 'clr', 'extensions' => $GLOBALS['TL_CONFIG']['validImageTypes']],
    'sql' => 'binary(16) NULL',
];

$GLOBALS['TL_DCA']['tl_module']['fields']['makler_headerImagePlaceholder'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_module']['makler_headerImagePlaceholder'],
    'exclude' => true,
    'inputType' => 'fileTree',
    'reference' => &$GLOBALS['TL_LANG']['tl_module'],
    'eval' => ['fieldType' => 'radio', 'filesOnly' => true, 'tl_class' => 'clr', 'extensions' => $GLOBALS['TL_CONFIG']['validImageTypes']],
    'sql' => 'binary(16) NULL',
];

$GLOBALS['TL_DCA']['tl_module']['fields']['immo_staticFilter'] = [
        'label' => &$GLOBALS['TL_LANG']['tl_module']['immo_staticFilter'],
        'exclude' => true,
        'inputType' => 'checkbox',
        'reference' => &$GLOBALS['TL_LANG']['tl_module'],
        'eval' => ['tl_class' => 'w50', 'mandatory' => false, 'isBoolean' => true],
        'sql' => "int(1) NOT NULL default '0'",
];

$GLOBALS['TL_DCA']['tl_module']['fields']['immo_listDebug'] = [
        'label' => &$GLOBALS['TL_LANG']['tl_module']['immo_listDebug'],
        'exclude' => true,
        'inputType' => 'checkbox',
        'reference' => &$GLOBALS['TL_LANG']['tl_module'],
        'eval' => ['includeBlankOption' => true, 'tl_class' => 'w50'],
        'sql' => "int(1) NOT NULL default '0'",
];

$GLOBALS['TL_DCA']['tl_module']['fields']['immo_listInSitemap'] = [
        'label' => &$GLOBALS['TL_LANG']['tl_module']['immo_listInSitemap'],
        'exclude' => true,
        'inputType' => 'checkbox',
        'reference' => &$GLOBALS['TL_LANG']['tl_module'],
        'eval' => ['includeBlankOption' => true, 'tl_class' => 'w50'],
        'sql' => "int(1) NOT NULL default '0'",
];

$GLOBALS['TL_DCA']['tl_module']['fields']['makler_addListPagination'] = [
        'label' => &$GLOBALS['TL_LANG']['tl_module']['makler_addListPagination'],
        'exclude' => true,
        'filter' => true,
        'inputType' => 'checkbox',
        'eval' => ['submitOnChange' => true],
        'sql' => "int(1) NOT NULL default '0'",
];
$GLOBALS['TL_DCA']['tl_module']['fields']['makler_paginationCount'] = [
        'label' => &$GLOBALS['TL_LANG']['tl_module']['makler_paginationCount'],
        'exclude' => true,
        'inputType' => 'text',
        'eval' => ['mandatory' => true, 'rgxp' => 'digit', 'tl_class' => 'w50'],
        'sql' => "int(2) NOT NULL default '10'",
];
$GLOBALS['TL_DCA']['tl_module']['fields']['makler_paginationLinkCount'] = [
        'label' => &$GLOBALS['TL_LANG']['tl_module']['makler_paginationLinkCount'],
        'exclude' => true,
        'inputType' => 'text',
        'eval' => ['mandatory' => true, 'rgxp' => 'digit', 'tl_class' => 'w50'],
        'sql' => "int(2) NOT NULL default '10'",
];

$GLOBALS['TL_DCA']['tl_module']['fields']['makler_paginationUseIsotope'] = [
        'label' => &$GLOBALS['TL_LANG']['tl_module']['makler_paginationUseIsotope'],
        'exclude' => true,
        'inputType' => 'checkbox',
        'default' => true,
        'reference' => &$GLOBALS['TL_LANG']['tl_module'],
        'eval' => ['tl_class' => 'w50', 'mandatory' => false, 'isBoolean' => true],
        'sql' => "int(1) NOT NULL default '1'",
];

$GLOBALS['TL_DCA']['tl_module']['fields']['makler_paginationShowtitle'] = [
        'label' => &$GLOBALS['TL_LANG']['tl_module']['makler_paginationShowtitle'],
        'exclude' => true,
        'filter' => true,
        'inputType' => 'checkbox',
        'eval' => ['tl_class' => 'w50'],
        'sql' => "int(1) NOT NULL default '0'",
];

$GLOBALS['TL_DCA']['tl_module']['fields']['makler_useModuleCss'] = [
        'label' => &$GLOBALS['TL_LANG']['tl_module']['makler_useModuleCss'],
        'exclude' => true,
        'inputType' => 'checkbox',
        'default' => true,
        'reference' => &$GLOBALS['TL_LANG']['tl_module'],
        'eval' => ['tl_class' => 'w50', 'mandatory' => false, 'isBoolean' => true],
        'sql' => "int(1) NOT NULL default '1'",
];

$GLOBALS['TL_DCA']['tl_module']['fields']['makler_useModuleDetailCss'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_module']['makler_useModuleDetailCss'],
    'exclude' => true,
    'inputType' => 'checkbox',
    'default' => true,
    'reference' => &$GLOBALS['TL_LANG']['tl_module'],
    'eval' => ['tl_class' => 'w50', 'mandatory' => false, 'isBoolean' => true],
    'sql' => "int(1) NOT NULL default '1'",
];

$GLOBALS['TL_DCA']['tl_module']['fields']['makler_useModuleJs'] = [
        'label' => &$GLOBALS['TL_LANG']['tl_module']['makler_useModuleJs'],
        'exclude' => true,
        'inputType' => 'checkbox',
        'default' => true,
        'reference' => &$GLOBALS['TL_LANG']['tl_module'],
        'eval' => ['tl_class' => 'w50', 'mandatory' => false, 'isBoolean' => true],
        'sql' => "int(1) NOT NULL default '1'",
];

$GLOBALS['TL_DCA']['tl_module']['fields']['makler_compatibilityMode'] = [
        'label' => &$GLOBALS['TL_LANG']['tl_module']['makler_compatibilityMode'],
        'exclude' => true,
        'inputType' => 'checkbox',
        'default' => false,
        'reference' => &$GLOBALS['TL_LANG']['tl_module'],
        'eval' => ['tl_class' => 'w50', 'mandatory' => false, 'isBoolean' => true],
        'sql' => "int(1) NOT NULL default '0'",
];

$GLOBALS['TL_DCA']['tl_module']['fields']['makler_showHeadline'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_module']['makler_showHeadline'],
    'inputType' => 'checkbox',
    'eval' => ['tl_class' => 'w50'],
    'sql' => "char(1) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_module']['fields']['makler_showBackgroundImage'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_module']['makler_showBackgroundImage'],
    'inputType' => 'checkbox',
    'eval' => ['tl_class' => 'w50'],
    'sql' => "char(1) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_module']['fields']['makler_showMap'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_module']['makler_showMap'],
    'exclude' => true,
    'inputType' => 'checkbox',
    'default' => true,
    'reference' => &$GLOBALS['TL_LANG']['tl_module'],
    'eval' => ['tl_class' => 'w50 clr', 'mandatory' => false, 'isBoolean' => true],
    'sql' => "int(1) NOT NULL default '1'",
];

$GLOBALS['TL_DCA']['tl_module']['fields']['makler_debug'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_module']['makler_debug'],
    'exclude' => true,
    'inputType' => 'checkbox',
    'reference' => &$GLOBALS['TL_LANG']['tl_module'],
    'eval' => ['includeBlankOption' => true, 'tl_class' => 'w50'],
    'sql' => "int(1) NOT NULL default '0'",
];

$GLOBALS['TL_DCA']['tl_module']['fields']['makler_filter_type'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_module']['makler_filter_type'],
    'exclude' => true,
    'inputType' => 'checkbox',
    'reference' => &$GLOBALS['TL_LANG']['tl_module'],
    'eval' => ['includeBlankOption' => true, 'tl_class' => 'w50'],
    'sql' => "int(1) NOT NULL default '0'",
];

$GLOBALS['TL_DCA']['tl_module']['fields']['makler_headerImageSource'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_module']['makler_headerImageSource'],
    'inputType' => 'select',
    'default' => 1,
    'options' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15 ,16, 17, 18, 19, 20],
    'reference' => &$GLOBALS['TL_LANG']['tl_module']['makler_headerImageSource_select'],
    'eval' => ['tl_class' => 'w50'],
    'sql' => "varchar(255) NOT NULL default ''",
];

class tl_module_makler extends Backend
{
    /*public function __construct() {
        parent::__construct();
        $this->import('BackendUser', 'User');
    }*/

    public function getModuleTemplates(DataContainer $dc)
    {
        if (version_compare(VERSION.BUILD, '2.9.0', '>=')) {
            return $this->getTemplateGroup('makler', $dc->activeRecord->pid);
        }

        return $this->getTemplateGroup('makler');
    }

    public function getFieldKeys($r)
    {
        $fieldKeysArr = ['-', '-'];
        foreach ($GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys'] as $key => $value) {
            $fieldKeysArr[$key] = $key;
        }
        sort($fieldKeysArr);

        return $fieldKeysArr;
    }
}
