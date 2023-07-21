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

/*
 * Miscellaneous.
 */
$GLOBALS['TL_LANG']['MSC']['totalPages'] = 'Страница %s из %s';
$GLOBALS['TL_LANG']['MSC']['points'] = '...';
$GLOBALS['TL_LANG']['MSC']['prices'] = 'ЦЕНА';
$GLOBALS['TL_LANG']['MSC']['area'] = 'ОПИСАНИЕ';
$GLOBALS['TL_LANG']['MSC']['distances'] = 'Расстояния';
$GLOBALS['TL_LANG']['MSC']['other'] = 'Другое';
$GLOBALS['TL_LANG']['MSC']['sampleOutputs'] = 'Выборочные вопросы';
$GLOBALS['TL_LANG']['MSC']['contact'] = 'Контактное лицо';
$GLOBALS['TL_LANG']['MSC']['moreObjectImages'] = 'Больше изображений объектов';
$GLOBALS['TL_LANG']['MSC']['documents'] = 'Документы';
$GLOBALS['TL_LANG']['MSC']['map'] = 'Карта';
$GLOBALS['TL_LANG']['MSC']['objectAddress'] = 'Адрес';
$GLOBALS['TL_LANG']['MSC']['yes'] = 'Да';
$GLOBALS['TL_LANG']['MSC']['no'] = 'Нет';
$GLOBALS['TL_LANG']['MSC']['energyCertificate'] = 'Энергопаспорт';
$GLOBALS['TL_LANG']['MSC']['energyDemandValue'] = 'Значение характеристики спроса на энергию';
$GLOBALS['TL_LANG']['MSC']['energyConsumptionIndex'] = 'Индекс энергопотребления';
$GLOBALS['TL_LANG']['MSC']['filterReset'] = 'Сбросить фильтр';
$GLOBALS['TL_LANG']['MSC']['searchEstate'] = 'Поиск недвижимости';
$GLOBALS['TL_LANG']['MSC']['all'] = 'Все';
$GLOBALS['TL_LANG']['MSC']['objectDetails'] = 'Детали объекта';
$GLOBALS['TL_LANG']['MSC']['placeholderImage'] = 'Изображение-заполнитель';
$GLOBALS['TL_LANG']['MSC']['notNecessary'] = 'Не нуждается';

$GLOBALS['TL_LANG']['CTE']['makler_headerImage'] = ['MaklerModul Заголовок', 'MaklerModul Заголовок'];
$GLOBALS['TL_LANG']['FMD']['immoHeaderImageView'] = ['MaklerModul Заголовок', 'MaklerModul Заголовок'];

$GLOBALS['TL_LANG']['MOD']['makler_modul_errors'] = [];
$GLOBALS['TL_LANG']['MOD']['makler_modul_errors']['no_detail_page'] = 'Пожалуйста, выберите страницу детализации в модуле Property List.';
$GLOBALS['TL_LANG']['MOD']['makler_modul_errors']['has-no-objects'] = '<p>На данный момент нет доступных объектов.</p>';

include_once 'makler_modul_mplus.php';
