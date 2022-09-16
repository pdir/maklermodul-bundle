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
 * Add palettes to tl_content.
 */
$GLOBALS['TL_DCA']['tl_content']['palettes']['makler_headerImage']
    = '{header_legend},headline,type,makler_showHeadline;{image_legend},size,makler_headerImageSource,makler_showBackgroundImage,makler_headerImagePlaceholder'
    .';{expert_legend:hide},guests,cssID'
    .';{invisible_legend:hide},invisible,start,stop';

/*
 * Add fields to tl_module
 */

$GLOBALS['TL_DCA']['tl_content']['fields']['makler_showHeadline'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_content']['makler_showHeadline'],
    'inputType' => 'checkbox',
    'eval' => ['tl_class' => 'w50'],
    'sql' => "char(1) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_content']['fields']['makler_showBackgroundImage'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_content']['makler_showBackgroundImage'],
    'inputType' => 'checkbox',
    'eval' => ['tl_class' => 'w50'],
    'sql' => "char(1) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_content']['fields']['makler_headerImagePlaceholder'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_content']['makler_headerImagePlaceholder'],
    'exclude' => true,
    'inputType' => 'fileTree',
    'reference' => &$GLOBALS['TL_LANG']['tl_content'],
    'eval' => ['fieldType' => 'radio', 'filesOnly' => true, 'tl_class' => 'w50', 'extensions' => $GLOBALS['TL_CONFIG']['validImageTypes']],
    'sql' => 'binary(16) NULL',
];

$GLOBALS['TL_DCA']['tl_content']['fields']['makler_headerImageSource'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_content']['makler_headerImageSource'],
    'inputType' => 'select',
    'default' => 1,
    'options' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15 ,16, 17, 18, 19, 20],
    'reference' => &$GLOBALS['TL_LANG']['tl_content']['makler_headerImageSource_select'],
    'eval' => ['tl_class' => 'w50'],
    'sql' => "varchar(255) NOT NULL default ''",
];
