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
$GLOBALS['TL_LANG']['MSC']['totalPages'] = 'Seite %s von %s';
$GLOBALS['TL_LANG']['MSC']['points'] = '...';
$GLOBALS['TL_LANG']['MSC']['prices'] = 'Preise';
$GLOBALS['TL_LANG']['MSC']['area'] = 'Flächen';
$GLOBALS['TL_LANG']['MSC']['distances'] = 'Entfernungen';
$GLOBALS['TL_LANG']['MSC']['other'] = 'Sonstiges';
$GLOBALS['TL_LANG']['MSC']['sampleOutputs'] = 'Beispielausgaben';
$GLOBALS['TL_LANG']['MSC']['contact'] = 'Ansprechpartner';
$GLOBALS['TL_LANG']['MSC']['moreObjectImages'] = 'Weitere Objektbilder';
$GLOBALS['TL_LANG']['MSC']['documents'] = 'Dokumente';
$GLOBALS['TL_LANG']['MSC']['map'] = 'Karte';
$GLOBALS['TL_LANG']['MSC']['objectAddress'] = 'Objektanschrift';
$GLOBALS['TL_LANG']['MSC']['yes'] = 'Ja';
$GLOBALS['TL_LANG']['MSC']['no'] = 'Nein';
$GLOBALS['TL_LANG']['MSC']['energyCertificate'] = 'Energieausweis';
$GLOBALS['TL_LANG']['MSC']['energyDemandValue'] = 'Energiebedarfskennwert';
$GLOBALS['TL_LANG']['MSC']['energyConsumptionIndex'] = 'Energieverbrauchskennwert';
$GLOBALS['TL_LANG']['MSC']['filterReset'] = 'Filter zurücksetzen';
$GLOBALS['TL_LANG']['MSC']['searchEstate'] = 'Immobilien suchen';
$GLOBALS['TL_LANG']['MSC']['all'] = 'Alle';
$GLOBALS['TL_LANG']['MSC']['objectDetails'] = 'Objektangaben';
$GLOBALS['TL_LANG']['MSC']['placeholderImage'] = 'Platzhalter-Bild';

$GLOBALS['TL_LANG']['CTE']['makler_headerImage'] = ['MaklerModul Kopfbild', 'MaklerModul Kopfbild'];
$GLOBALS['TL_LANG']['FMD']['immoHeaderImageView'] = ['MaklerModul Kopfbild', 'MaklerModul Kopfbild'];

$GLOBALS['TL_LANG']['MOD']['makler_modul_errors'] = [];
$GLOBALS['TL_LANG']['MOD']['makler_modul_errors']['no_detail_page'] = 'Bitte wähle eine Detailseite im Modul Immobilienliste aus.';
$GLOBALS['TL_LANG']['MOD']['makler_modul_errors']['has-no-objects'] = '<p>Im Moment sind keine Objekte verfügbar.</p>';

include_once 'makler_modul_mplus.php';
