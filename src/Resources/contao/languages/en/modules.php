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

use Contao\Config;
use Contao\System;

/**
 * Module translation.
 */
if (!isset($GLOBALS['TL_LANG']['MOD']['pdir'])) {
    $GLOBALS['TL_LANG']['MOD']['pdir']= [];
}
$GLOBALS['TL_LANG']['MOD']['pdir'] = ['pdir Apps', 'Enthält alle Apps aus dem Hause pdir.'];
$GLOBALS['TL_LANG']['MOD']['maklermodul']['type'] = 'Maklermodul';
$GLOBALS['TL_LANG']['MOD']['maklermodul'][0] = 'Maklermodul';
$GLOBALS['TL_LANG']['MOD']['maklermodul'][1] = 'Organize your Maklermodul here';

$GLOBALS['TL_LANG']['MOD']['maklermodul']['greeting'] = 'Welcome to %s Bundle for Contao';
$GLOBALS['TL_LANG']['MOD']['maklermodul']['text'] = 'The broker module for Contao forms the interface between your broker software and the content management system Contao. <br> The data is automatically imported here, displayed as a filterable object list and presented in the detailed view as an exposé according to the guidelines of the corporate design of the brokerage offices.';
$GLOBALS['TL_LANG']['MOD']['maklermodul']['tools'] = 'Tools';
$GLOBALS['TL_LANG']['MOD']['maklermodul']['help_h2'] = 'Help & links';
$GLOBALS['TL_LANG']['MOD']['maklermodul']['optionalBundles'] = 'Optional extensions';

$GLOBALS['TL_LANG']['MOD']['maklermodul']['buttons'] = [
    ['href' => "contao/main.php?do=maklermodulSetup&act=emptyDataFolder&ref=" . System::getContainer()->get('request_stack')->getCurrentRequest()->get('_contao_referer_id'), 'onclick' => "if(!confirm('Should the folder " . Config::get('uploadPath').'/maklermodul/' . "data really be emptied??'))return false;Backend.getScrollOffset()", 'target' => '_blank', 'alt' => $GLOBALS['TL_LANG']['MOD']['maklerSetup']['label']['emptyDataFolder'], 'image' => 'bundles/pdirmaklermodul/img/icon_delete.png'],
    ['href' => "contao/main.php?do=maklermodulSetup&act=emptyTmpFolder&ref=" . System::getContainer()->get('request_stack')->getCurrentRequest()->get('_contao_referer_id'), 'onclick' => "if(!confirm('Should the folder " . Config::get('uploadPath').'/maklermodul/' . "org really be emptied??'))return false;Backend.getScrollOffset()", 'target' => '_blank', 'alt' => $GLOBALS['TL_LANG']['MOD']['maklerSetup']['label']['emptyTmpFolder'], 'image' => 'bundles/pdirmaklermodul/img/icon_delete.png'],
    ['href' => "contao/main.php?do=maklermodulSetup&act=emptyUploadFolder&ref=" . System::getContainer()->get('request_stack')->getCurrentRequest()->get('_contao_referer_id'), 'onclick' => "if(!confirm('Should the folder " . Config::get('uploadPath').'/maklermodul/' . "upload really be emptied??'))return false;Backend.getScrollOffset()", 'target' => '_blank', 'alt' => $GLOBALS['TL_LANG']['MOD']['maklerSetup']['label']['emptyUploadFolder'], 'image' => 'bundles/pdirmaklermodul/img/icon_delete.png'],
    ['href' => "contao/main.php?do=maklermodulSetup&act=downloadDemoData&ref=" . System::getContainer()->get('request_stack')->getCurrentRequest()->get('_contao_referer_id'), 'target' => '_blank', 'alt' => $GLOBALS['TL_LANG']['MOD']['maklerSetup']['label']['downloadDemoData'], 'image' => 'bundles/pdirmaklermodul/img/icon_demodata.png'],
];

$GLOBALS['TL_LANG']['MOD']['maklermodul']['setupLinks'] = [
    ['href' => 'https://pdir.de/docs/de/contao/extensions/maklermodul/', 'target' => '_blank', 'html' => 'Documentation'],
    ['href' => 'https://github.com/pdir/maklermodul-bundle/issues', 'target' => '_blank', 'html' => 'Report issues'],
    ['href' => 'https://github.com/pdir/maklermodul-bundle/', 'target' => '_blank', 'html' => 'Github'],
    ['href' => 'https://www.maklermodul.de/', 'target' => '_blank', 'html' => 'Demo'],
];

$GLOBALS['TL_LANG']['MOD']['maklermodul']['editions']['free'] = [
    'payment' => 'free',
    'product' => 'Free',
    'teaser' => 'Start with the basics. Show your estates.',
    //'button_text' => 'jetzt herunterladen',
    'features' => ['+Maintain ratings', '+1 standard template', '-'],
];
$GLOBALS['TL_LANG']['MOD']['maklermodul']['editions']['openImmoSync'] = [
    'payment' => 'once, plus VAT',
    'product' => 'Open Immo Sync',
    'teaser' => 'Automate! Import your estates via Open Immo XML Format fully automatically.',
    'button_text' => 'buy',
    'features' => [
        '+Maintain ratings',
        '+1 standard template',
        '-',
        '*Effective presentation as a star rating, carousel, sticker or card',
        '*Display of evaluation texts (depending on the template)',
        '*Display detailed business information like name, website, etc ...',
    ],
];
$GLOBALS['TL_LANG']['MOD']['maklermodul']['editions']['immoscoutSync'] = [
    'payment' => 'once, plus VAT',
    'product' => 'Immobilienscout24 API Sync',
    'teaser' => "Automate! Import your estates from immobilienscout24.de fully automatically.",
    'button_text' => 'buy',
    'features' => [
        '+Maintain ratings',
        '+1 standard template',
        '-',
        '*Effective presentation as a star rating, carousel, sticker or card',
        '*Display of evaluation texts (depending on the template)',
        '*Display detailed business information like name, website, etc ...',
        '*Fully automatic sync of your ratings from <strong>one platform<sup>*</sup></strong>',
    ],
];
