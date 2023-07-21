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

use Contao\Config;
use Contao\System;

/*
 * Module translation.
 */
if (!isset($GLOBALS['TL_LANG']['MOD']['pdir'])) {
    $GLOBALS['TL_LANG']['MOD']['pdir'] = [];
}
$GLOBALS['TL_LANG']['MOD']['pdir'] = ['pdir Приложения', 'Содержит все приложения из pdir.'];
$GLOBALS['TL_LANG']['MOD']['maklermodul']['type'] = 'Брокерский модуль';
$GLOBALS['TL_LANG']['MOD']['maklermodul'][0] = 'Брокерский модуль';
$GLOBALS['TL_LANG']['MOD']['maklermodul'][1] = 'Организуйте свой Маклермодуль здесь';

$GLOBALS['TL_LANG']['MOD']['maklermodul']['greeting'] = 'Добро пожаловать в %s Пакет для Contao';
$GLOBALS['TL_LANG']['MOD']['maklermodul']['text'] = 'Модуль брокера для Contao формирует интерфейс между вашим брокерским ПО и системой управления контентом Contao. <br> Данные автоматически импортируются сюда, отображаются в виде фильтруемого списка объектов и представляются в детальном виде в виде экспозиции в соответствии с рекомендациями корпоративного дизайна брокерских офисов.';
$GLOBALS['TL_LANG']['MOD']['maklermodul']['tools'] = 'Инструменты';
$GLOBALS['TL_LANG']['MOD']['maklermodul']['help_h2'] = 'Помощь и ссылки';
$GLOBALS['TL_LANG']['MOD']['maklermodul']['optionalBundles'] = 'Дополнительные расширения';

$GLOBALS['TL_LANG']['MOD']['maklerSetup']['label']['emptyDataFolder'] = 'Пустая папка с данными';
$GLOBALS['TL_LANG']['MOD']['maklerSetup']['label']['emptyTmpFolder'] = 'Пустая папка temp';
$GLOBALS['TL_LANG']['MOD']['maklerSetup']['label']['emptyUploadFolder'] = 'Пустая папка загрузки';
$GLOBALS['TL_LANG']['MOD']['maklerSetup']['label']['downloadDemoData'] = 'Скачать демо-данные';

$GLOBALS['TL_LANG']['MOD']['maklermodul']['buttons'] = [
    ['href' => 'contao/main.php?do=maklermodulSetup&act=emptyDataFolder&ref='.System::getContainer()->get('request_stack')->getCurrentRequest()->get('_contao_referer_id'), 'onclick' => "if(!confirm('Действительно ли папка ".Config::get('uploadPath').'/maklermodul/'."data должна быть очищена?'))return false;Backend.getScrollOffset()", 'target' => '_blank', 'alt' => $GLOBALS['TL_LANG']['MOD']['maklerSetup']['label']['emptyDataFolder'], 'image' => 'bundles/pdirmaklermodul/img/icon_delete.png'],
    ['href' => 'contao/main.php?do=maklermodulSetup&act=emptyTmpFolder&ref='.System::getContainer()->get('request_stack')->getCurrentRequest()->get('_contao_referer_id'), 'onclick' => "if(!confirm('Действительно ли папка ".Config::get('uploadPath').'/maklermodul/'."org должна быть очищена?'))return false;Backend.getScrollOffset()", 'target' => '_blank', 'alt' => $GLOBALS['TL_LANG']['MOD']['maklerSetup']['label']['emptyTmpFolder'], 'image' => 'bundles/pdirmaklermodul/img/icon_delete.png'],
    ['href' => 'contao/main.php?do=maklermodulSetup&act=emptyUploadFolder&ref='.System::getContainer()->get('request_stack')->getCurrentRequest()->get('_contao_referer_id'), 'onclick' => "if(!confirm('Действительно ли папка ".Config::get('uploadPath').'/maklermodul/'."upload должна быть очищена?'))return false;Backend.getScrollOffset()", 'target' => '_blank', 'alt' => $GLOBALS['TL_LANG']['MOD']['maklerSetup']['label']['emptyUploadFolder'], 'image' => 'bundles/pdirmaklermodul/img/icon_delete.png'],
    ['href' => 'contao/main.php?do=maklermodulSetup&act=downloadDemoData&ref='.System::getContainer()->get('request_stack')->getCurrentRequest()->get('_contao_referer_id'), 'target' => '_blank', 'alt' => $GLOBALS['TL_LANG']['MOD']['maklerSetup']['label']['downloadDemoData'], 'image' => 'bundles/pdirmaklermodul/img/icon_demodata.png'],
];

$GLOBALS['TL_LANG']['MOD']['maklermodul']['setupLinks'] = [
    ['href' => 'https://pdir.de/docs/de/contao/extensions/maklermodul/', 'target' => '_blank', 'html' => 'Документация'],
    ['href' => 'https://github.com/pdir/maklermodul-bundle/issues', 'target' => '_blank', 'html' => 'Вопросы отчетности'],
    ['href' => 'https://github.com/pdir/maklermodul-bundle/', 'target' => '_blank', 'html' => 'Github'],
    ['href' => 'https://www.maklermodul.de/', 'target' => '_blank', 'html' => 'Демоверсия'],
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
