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
 * Miscellaneous.
 */
$GLOBALS['TL_LANG']['MSC']['totalPages'] = 'Seite %s von %s';
$GLOBALS['TL_LANG']['MSC']['points'] = '...';

$GLOBALS['TL_LANG']['CTE']['makler_headerImage'] = ['MaklerModul Kopfbild', 'MaklerModul Kopfbild'];
$GLOBALS['TL_LANG']['FMD']['immoHeaderImageView'] = ['MaklerModul Kopfbild', 'MaklerModul Kopfbild'];

$strErrorDefault = 'Makler Modul Fehler: ';
$GLOBALS['TL_LANG']['MOD']['makler_modul_mplus']['error']['no_detail_page'] = $strErrorDefault.'Bitte w&auml;hlen Sie eine Detailansicht in Ihrer Listenansicht aus.';

include_once 'makler_modul_mplus.php';
