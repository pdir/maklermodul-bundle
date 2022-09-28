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

use Contao\Config;
use Contao\System;

/*
 * Module translation.
 */
if (!isset($GLOBALS['TL_LANG']['MOD']['pdir'])) {
    $GLOBALS['TL_LANG']['MOD']['pdir'] = [];
}
$GLOBALS['TL_LANG']['MOD']['pdir'] = ['pdir Apps', 'Contains all apps from pdir.'];
$GLOBALS['TL_LANG']['MOD']['maklermodulSetup'][0] = 'Maklermodul';
$GLOBALS['TL_LANG']['MOD']['maklermodulSetup'][1] = 'Verwalten Sie hier Ihr Maklermodul';

$GLOBALS['TL_LANG']['MOD']['maklermodul']['type'] = 'Maklermodul';

$GLOBALS['TL_LANG']['MOD']['maklermodul'][0] = 'Maklermodul';
$GLOBALS['TL_LANG']['MOD']['maklermodul'][1] = 'Verwalten Sie hier Ihre Immobilien';

$GLOBALS['TL_LANG']['MOD']['maklermodul']['greeting'] = 'Willkommen beim %s Bundle für Contao';
$GLOBALS['TL_LANG']['MOD']['maklermodul']['text'] = 'Das Maklermodul für Contao bildet die Schnittstelle zwischen Ihrer Maklersoftware und dem Content-Management-System Contao. <br> Die Daten werden hierbei automatisch importiert, als filterbare Objektliste angezeigt und in der Detailansicht nach den Richtlinien des Corporate Design der Maklerbüros als Exposé dargestellt.';
$GLOBALS['TL_LANG']['MOD']['maklermodul']['tools'] = 'Tools';
$GLOBALS['TL_LANG']['MOD']['maklermodul']['help_h2'] = 'Hilfe & Links';
$GLOBALS['TL_LANG']['MOD']['maklermodul']['optionalBundles'] = 'Optionale Erweiterungen';

$GLOBALS['TL_LANG']['MOD']['maklermodul']['buttons'] = [
    ['href' => 'contao/main.php?do=maklermodulSetup&act=emptyDataFolder&ref='.System::getContainer()->get('request_stack')->getCurrentRequest()->get('_contao_referer_id'), 'onclick' => "if(!confirm('Soll der Ordner ".Config::get('uploadPath').'/maklermodul/'."data wirklich geleert werden?'))return false;Backend.getScrollOffset()", 'target' => '_blank', 'alt' => $GLOBALS['TL_LANG']['MOD']['maklerSetup']['label']['emptyDataFolder'], 'image' => 'bundles/pdirmaklermodul/img/icon_delete.png'],
    ['href' => 'contao/main.php?do=maklermodulSetup&act=emptyTmpFolder&ref='.System::getContainer()->get('request_stack')->getCurrentRequest()->get('_contao_referer_id'), 'onclick' => "if(!confirm('Soll der Ordner ".Config::get('uploadPath').'/maklermodul/'."org wirklich geleert werden?'))return false;Backend.getScrollOffset()", 'target' => '_blank', 'alt' => $GLOBALS['TL_LANG']['MOD']['maklerSetup']['label']['emptyTmpFolder'], 'image' => 'bundles/pdirmaklermodul/img/icon_delete.png'],
    ['href' => 'contao/main.php?do=maklermodulSetup&act=emptyUploadFolder&ref='.System::getContainer()->get('request_stack')->getCurrentRequest()->get('_contao_referer_id'), 'onclick' => "if(!confirm('Soll der Ordner ".Config::get('uploadPath').'/maklermodul/'."upload wirklich geleert werden?'))return false;Backend.getScrollOffset()", 'target' => '_blank', 'alt' => $GLOBALS['TL_LANG']['MOD']['maklerSetup']['label']['emptyUploadFolder'], 'image' => 'bundles/pdirmaklermodul/img/icon_delete.png'],
    ['href' => 'contao/main.php?do=maklermodulSetup&act=downloadDemoData&ref='.System::getContainer()->get('request_stack')->getCurrentRequest()->get('_contao_referer_id'), 'target' => '_blank', 'alt' => $GLOBALS['TL_LANG']['MOD']['maklerSetup']['label']['downloadDemoData'], 'image' => 'bundles/pdirmaklermodul/img/icon_demodata.png'],
];

$GLOBALS['TL_LANG']['MOD']['maklermodul']['setupLinks'] = [
    ['href' => 'https://pdir.de/docs/de/contao/extensions/maklermodul/', 'target' => '_blank', 'html' => 'Dokumentation'],
    ['href' => 'https://github.com/pdir/maklermodul-bundle/issues', 'target' => '_blank', 'html' => 'Probleme melden'],
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
    'teaser' => 'Automate! Import your estates from immobilienscout24.de fully automatically.',
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
