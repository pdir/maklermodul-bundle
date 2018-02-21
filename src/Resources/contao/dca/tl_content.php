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
 * Add palettes to tl_content
 */

$GLOBALS['TL_DCA']['tl_content']['palettes']['makler_headerImage']
	= '{header_legend},headline,type,makler_showHeadline,makler_showBackgroundImage,makler_headerImagePlaceholder'
	. ';{expert_legend:hide},guests,cssID,space'
	. ';{invisible_legend:hide},invisible,start,stop';

/**
 * Add fields to tl_module
 */

$GLOBALS['TL_DCA']['tl_content']['fields']['makler_showHeadline'] = array
(
	'label'     => &$GLOBALS['TL_LANG']['tl_content']['makler_showHeadline'],
	'inputType' => 'checkbox',
	'eval'      => array('tl_class' => 'w50'),
	'sql'       => "char(1) NOT NULL default ''",
);

$GLOBALS['TL_DCA']['tl_content']['fields']['makler_showBackgroundImage'] = array
(
    'label'     => &$GLOBALS['TL_LANG']['tl_content']['makler_showBackgroundImage'],
    'inputType' => 'checkbox',
    'eval'      => array('tl_class' => 'w50'),
    'sql'       => "char(1) NOT NULL default ''",
);

$GLOBALS['TL_DCA']['tl_content']['fields']['makler_headerImagePlaceholder'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_content']['makler_headerImagePlaceholder'],
    'exclude'                 => true,
    'inputType'               => 'fileTree',
    'reference'               => &$GLOBALS['TL_LANG']['tl_content'],
    'eval'                    => array('fieldType'=>'radio', 'filesOnly'=>true, 'tl_class'=>'clr', 'extensions'=>$GLOBALS['TL_CONFIG']['validImageTypes']),
    'sql'                     => "binary(16) NULL"
);
