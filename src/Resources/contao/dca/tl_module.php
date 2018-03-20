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
 * Add palettes to tl_module
 */

$GLOBALS['TL_DCA']['tl_module']['palettes']['immoListView']
	= '{title_legend},name,headline,type;'
	. '{template_legend},immo_readerPage,immo_listTemplate;'
	. '{field_legend},immo_listContent,immo_listFilter,makler_listFilterTemplate;'
	. '{makler_sort_legend},immo_listSort,makler_listSortType,makler_listSortAsc;'
	. '{makler_cond_legend},immo_listCondition;'
	. '{image_legend},immo_listImageWidth,immo_listImageHeight,immo_listImageMode,imgSize,makler_listViewPlaceholder;'
	. '{makler_pagination_legend},makler_addListPagination;'
	. '{option_legend},immo_staticFilter,immo_filterListPage,immo_listInSitemap,immo_listDebug,makler_useModuleCss,makler_useModuleJs,makler_compatibilityMode';

$GLOBALS['TL_DCA']['tl_module']['palettes']['immoDetailView']
	= '{title_legend},name,headline,type;'
	. '{template_legend},immo_listPage,immo_readerTemplate,makler_showMap,makler_gmapApiKey,makler_detailViewPlaceholder;'
	. '{image_legend},imgSize;'
    . '{option_legend},makler_useModuleDetailCss';

$GLOBALS['TL_DCA']['tl_module']['palettes']['immoHeaderImageView']
	= '{title_legend},name,headline,type,makler_showHeadline,makler_showBackgroundImage,makler_headerImagePlaceholder;'
	. '{expert_legend:hide},guests,cssID,space;';

$GLOBALS['TL_DCA']['tl_module']['palettes']['__selector__'][] = 'makler_addListPagination';
$GLOBALS['TL_DCA']['tl_module']['subpalettes']['makler_addListPagination'] = 'makler_paginationCount,makler_paginationLinkCount,makler_paginationUseIsotope'; // ,makler_paginationShowtitle';

/**
 * Add fields to tl_module
 */

$GLOBALS['TL_DCA']['tl_module']['fields']['immo_actIndexFile'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['immo_actIndexFile'],
    'exclude'                 => true,
    'inputType'               => 'text',
    'sql'                     =>  "text NOT NULL default '00index-demo-00001.json'"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['immo_readerTemplate'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['immo_readerTemplate'],
    'exclude'                 => true,
    'inputType'               => 'select',
    'options_callback'        => array('tl_module_makler', 'getModuleTemplates'),
    'reference'               => &$GLOBALS['TL_LANG']['tl_module'],
    'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50'),
    'sql'                     =>  "varchar(32) NOT NULL default '0'"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['immo_readerPage'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['immo_readerPage'],
	'exclude'                 => true,
	'inputType'               => 'pageTree',
	'reference'               => &$GLOBALS['TL_LANG']['tl_module'],
	'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50'),
	'sql'                     => "int(10) unsigned NOT NULL default '0'"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['immo_listTemplate'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['immo_listTemplate'],
    'exclude'                 => true,
    'inputType'               => 'select',
    'options_callback'        => array('tl_module_makler', 'getModuleTemplates'),
    'reference'               => &$GLOBALS['TL_LANG']['tl_module'],
    'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50'),
    'sql'                     =>  "varchar(32) NOT NULL default '0'"
);


$GLOBALS['TL_DCA']['tl_module']['fields']['immo_listContent'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['immo_listContent'],
    'exclude'                 => true,
    'inputType'               => 'textarea',
    'reference'               => &$GLOBALS['TL_LANG']['tl_module'],
   // 'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50'),
    'sql'                     => "text NULL"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['immo_listPage'] = array
(
		'label'                   => &$GLOBALS['TL_LANG']['tl_module']['immo_listPage'],
		'exclude'                 => true,
		'inputType'               => 'pageTree',
		'reference'               => &$GLOBALS['TL_LANG']['tl_module'],
		'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50 autoheight'),
		'sql'                     => "int(10) unsigned NOT NULL default '0'"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['immo_filterListPage'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['immo_filterListPage'],
    'exclude'                 => true,
    'inputType'               => 'pageTree',
    'reference'               => &$GLOBALS['TL_LANG']['tl_module'],
    'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50 autoheight'),
    'sql'                     => "int(10) unsigned NOT NULL default '0'"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['immo_listFilter'] = array
(
		'label'                   => &$GLOBALS['TL_LANG']['tl_module']['immo_listFilter'],
		'exclude'                 => true,
		'inputType'               => 'textarea',
		'reference'               => &$GLOBALS['TL_LANG']['tl_module'],
		// 'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50'),
		'sql'                     => "text NULL"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['makler_listFilterTemplate'] = array
(
		'label'						=> &$GLOBALS['TL_LANG']['tl_module']['makler_listFilterTemplate'],
		'inputType'               	=> 'select',
		'default'				  	=> 'button',
		'options'				  	=> array('select','button'),
		'reference'               	=> &$GLOBALS['TL_LANG']['tl_module']['makler_listFilterTemplateOptions'],
		'eval'                    	=> array('tl_class'=>'w50'),
		'sql'                     	=> "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['immo_listSort'] = array
(
	'label'						=> &$GLOBALS['TL_LANG']['tl_module']['immo_listSort'],
	'inputType'               	=> 'select',
	'default'				  	=> '',
	'options_callback'        => array('tl_module_makler', 'getFieldKeys'),
	'reference'               	=> &$GLOBALS['TL_LANG']['tl_module'],
	'eval'                    	=> array('tl_class'=>'w50'),
	'sql'                     	=> "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['makler_listSortAsc'] = array
(
	'label'						=> &$GLOBALS['TL_LANG']['tl_module']['makler_listSortAsc'],
	'inputType'               	=> 'select',
	'default'				  	=> '',
	'options'				  	=> array('true','false'),
	'reference'               	=> &$GLOBALS['TL_LANG']['tl_module']['makler_listSortAscOptions'],
	'eval'                    	=> array('tl_class'=>'w50'),
	'sql'                     	=> "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['makler_listSortType'] = array
(
	'label'						=> &$GLOBALS['TL_LANG']['tl_module']['makler_listSortType'],
	'inputType'               	=> 'select',
	'default'				  	=> '',
	'options'				  	=> array('int','float','text'),
	'reference'               	=> &$GLOBALS['TL_LANG']['tl_module']['makler_listSortTypeOptions'],
	'eval'                    	=> array('tl_class'=>'w50'),
	'sql'                     	=> "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['immo_listCondition'] = array
(
		'label'                   => &$GLOBALS['TL_LANG']['tl_module']['immo_listCondition'],
		'exclude'                 => true,
		'inputType'               => 'textarea',
		'reference'               => &$GLOBALS['TL_LANG']['tl_module'],
		'sql'                     => "text NULL"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['immo_listImageWidth'] = array
(
		'label'                   => &$GLOBALS['TL_LANG']['tl_module']['immo_listImageWidth'],
		'exclude'                 => true,
		'inputType'               => 'text',
		'reference'               => &$GLOBALS['TL_LANG']['tl_module'],
		'eval'                    => array('tl_class'=>'w50', 'mandatory'=>false, 'maxlength' => 255),
		'sql'                     => "varchar(255) NOT NULL default '330'"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['immo_listImageHeight'] = array
(
		'label'                   => &$GLOBALS['TL_LANG']['tl_module']['immo_listImageHeight'],
		'exclude'                 => true,
		'inputType'               => 'text',
		'reference'               => &$GLOBALS['TL_LANG']['tl_module'],
		'eval'                    => array('tl_class'=>'w50', 'mandatory'=>false, 'maxlength' => 255),
		'sql'                     => "varchar(255) NOT NULL default '270'"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['immo_listImageMode'] = array
(
		'label'                   => &$GLOBALS['TL_LANG']['tl_module']['immo_listImageMode'],
		'exclude'                 => true,
		'default'				  => 'center_center',
		'inputType'               => 'select',
		'options'				  => array('proportional','box','left_top','center_top','right_top','left_center','center_center','right_center','left_bottom','center_bottom','right_bottom'),
		// 'reference'               => &$GLOBALS['TL_LANG']['tl_module'],
		'eval'                    => array('tl_class'=>'w50', 'mandatory'=>false, 'maxlength' => 255),
		'sql'                     => "varchar(255) NOT NULL default 'center_center'"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['makler_listViewPlaceholder'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['makler_listViewPlaceholder'],
    'exclude'                 => true,
    'inputType'               => 'fileTree',
    'reference'               => &$GLOBALS['TL_LANG']['tl_module'],
    'eval'                    => array('fieldType'=>'radio', 'filesOnly'=>true, 'tl_class'=>'clr', 'extensions'=>$GLOBALS['TL_CONFIG']['validImageTypes']),
    'sql'                     => "binary(16) NULL"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['makler_detailViewPlaceholder'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['makler_detailViewPlaceholder'],
    'exclude'                 => true,
    'inputType'               => 'fileTree',
    'reference'               => &$GLOBALS['TL_LANG']['tl_module'],
    'eval'                    => array('fieldType'=>'radio', 'filesOnly'=>true, 'tl_class'=>'clr', 'extensions'=>$GLOBALS['TL_CONFIG']['validImageTypes']),
    'sql'                     => "binary(16) NULL"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['makler_headerImagePlaceholder'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['makler_headerImagePlaceholder'],
    'exclude'                 => true,
    'inputType'               => 'fileTree',
    'reference'               => &$GLOBALS['TL_LANG']['tl_module'],
    'eval'                    => array('fieldType'=>'radio', 'filesOnly'=>true, 'tl_class'=>'clr', 'extensions'=>$GLOBALS['TL_CONFIG']['validImageTypes']),
    'sql'                     => "binary(16) NULL"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['immo_staticFilter'] = array
(
		'label'                   => &$GLOBALS['TL_LANG']['tl_module']['immo_staticFilter'],
		'exclude'                 => true,
		'inputType'               => 'checkbox',
		'reference'               => &$GLOBALS['TL_LANG']['tl_module'],
		'eval'                    => array('tl_class'=>'w50', 'mandatory'=>false, 'isBoolean' => true),
		'sql'                     =>  "int(1) NOT NULL default '0'"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['immo_listDebug'] = array
(
		'label'                   => &$GLOBALS['TL_LANG']['tl_module']['immo_listDebug'],
		'exclude'                 => true,
		'inputType'               => 'checkbox',
		'reference'               => &$GLOBALS['TL_LANG']['tl_module'],
		'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50'),
		'sql'                     =>  "int(1) NOT NULL default '0'"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['immo_listInSitemap'] = array
(
		'label'                   => &$GLOBALS['TL_LANG']['tl_module']['immo_listInSitemap'],
		'exclude'                 => true,
		'inputType'               => 'checkbox',
		'reference'               => &$GLOBALS['TL_LANG']['tl_module'],
		'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50'),
		'sql'                     =>  "int(1) NOT NULL default '0'"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['makler_addListPagination'] = array
(
		'label' 					=> &$GLOBALS['TL_LANG']['tl_module']['makler_addListPagination'],
		'exclude' 					=> true,
		'filter' 					=> true,
		'inputType' 				=> 'checkbox',
		'eval' 						=> array ('submitOnChange' => true),
        'sql'                       =>  "int(1) NOT NULL default '0'"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['makler_paginationCount'] = array
(
		'label'						=> &$GLOBALS['TL_LANG']['tl_module']['makler_paginationCount'],
		'exclude'					=> true,
		'inputType'					=> 'text',
		'eval'						=> array('mandatory' => true,'rgxp' => 'digit','tl_class' => 'w50'),
        'sql'                       =>  "int(2) NOT NULL default '10'"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['makler_paginationLinkCount'] = array
(
		'label'						=> &$GLOBALS['TL_LANG']['tl_module']['makler_paginationLinkCount'],
		'exclude'					=> true,
		'inputType'					=> 'text',
        'eval'						=> array('mandatory' => true,'rgxp' => 'digit','tl_class' => 'w50'),
        'sql'                       =>  "int(2) NOT NULL default '10'"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['makler_paginationUseIsotope'] = array
(
		'label'                   => &$GLOBALS['TL_LANG']['tl_module']['makler_paginationUseIsotope'],
		'exclude'                 => true,
		'inputType'               => 'checkbox',
		'default'                  => true,
		'reference'               => &$GLOBALS['TL_LANG']['tl_module'],
		'eval'                    => array('tl_class'=>'w50', 'mandatory'=>false, 'isBoolean' => true),
		'sql'                     =>  "int(1) NOT NULL default '1'"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['makler_paginationShowtitle'] = array
(
		'label'						=> &$GLOBALS['TL_LANG']['tl_module']['makler_paginationShowtitle'],
		'exclude'					=> true,
		'filter'					=> true,
		'inputType'					=> 'checkbox',
		'eval'						=> array('tl_class' => 'w50'),
        'sql'                       =>  "int(1) NOT NULL default '0'"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['makler_useModuleCss'] = array
(
		'label'                   => &$GLOBALS['TL_LANG']['tl_module']['makler_useModuleCss'],
		'exclude'                 => true,
		'inputType'               => 'checkbox',
		'default'                  => true,
		'reference'               => &$GLOBALS['TL_LANG']['tl_module'],
		'eval'                    => array('tl_class'=>'w50', 'mandatory'=>false, 'isBoolean' => true),
		'sql'                     =>  "int(1) NOT NULL default '1'"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['makler_useModuleDetailCss'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['makler_useModuleDetailCss'],
    'exclude'                 => true,
    'inputType'               => 'checkbox',
    'default'                  => true,
    'reference'               => &$GLOBALS['TL_LANG']['tl_module'],
    'eval'                    => array('tl_class'=>'w50', 'mandatory'=>false, 'isBoolean' => true),
    'sql'                     =>  "int(1) NOT NULL default '1'"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['makler_useModuleJs'] = array
(
		'label'                   => &$GLOBALS['TL_LANG']['tl_module']['makler_useModuleJs'],
		'exclude'                 => true,
		'inputType'               => 'checkbox',
		'default'                  => true,
		'reference'               => &$GLOBALS['TL_LANG']['tl_module'],
		'eval'                    => array('tl_class'=>'w50', 'mandatory'=>false, 'isBoolean' => true),
		'sql'                     =>  "int(1) NOT NULL default '1'"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['makler_compatibilityMode'] = array
(
		'label'                   => &$GLOBALS['TL_LANG']['tl_module']['makler_compatibilityMode'],
		'exclude'                 => true,
		'inputType'               => 'checkbox',
		'default'                  => false,
		'reference'               => &$GLOBALS['TL_LANG']['tl_module'],
		'eval'                    => array('tl_class'=>'w50', 'mandatory'=>false, 'isBoolean' => true),
		'sql'                     =>  "int(1) NOT NULL default '0'"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['makler_gmapApiKey'] = array
(
		'label'						=> &$GLOBALS['TL_LANG']['tl_module']['makler_gmapApiKey'],
		'inputType'               	=> 'text',
		'default'				  	=> false,
		'reference'               	=> &$GLOBALS['TL_LANG']['tl_module']['makler_gmapApiKey'],
		'eval'                    	=> array('tl_class'=>'w50'),
		'sql'                     	=> "varchar(64) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['makler_showHeadline'] = array
(
	'label'     => &$GLOBALS['TL_LANG']['tl_module']['makler_showHeadline'],
	'inputType' => 'checkbox',
	'eval'      => array('tl_class' => 'w50'),
	'sql'       => "char(1) NOT NULL default ''",
);

$GLOBALS['TL_DCA']['tl_module']['fields']['makler_showBackgroundImage'] = array
(
    'label'     => &$GLOBALS['TL_LANG']['tl_module']['makler_showBackgroundImage'],
    'inputType' => 'checkbox',
    'eval'      => array('tl_class' => 'w50'),
    'sql'       => "char(1) NOT NULL default ''",
);

$GLOBALS['TL_DCA']['tl_module']['fields']['makler_showMap'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['makler_showMap'],
    'exclude'                 => true,
    'inputType'               => 'checkbox',
    'default'                  => true,
    'reference'               => &$GLOBALS['TL_LANG']['tl_module'],
    'eval'                    => array('tl_class'=>'w50 clr', 'mandatory'=>false, 'isBoolean' => true),
    'sql'                     =>  "int(1) NOT NULL default '1'"
);

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
        } else {
            return $this->getTemplateGroup('makler');
        }
    }

	public function getFieldKeys($r)
	{
		$fieldKeysArr = array('-','-');
		foreach ($GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys'] as $key=>$value){
			$fieldKeysArr[$key] = $key;
		}
		return $fieldKeysArr;
	}

}
