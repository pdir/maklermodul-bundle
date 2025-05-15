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
$GLOBALS['TL_LANG']['MSC']['totalPages'] = 'Page %s of %s';
$GLOBALS['TL_LANG']['MSC']['points'] = '...';
$GLOBALS['TL_LANG']['MSC']['prices'] = 'Prices';
$GLOBALS['TL_LANG']['MSC']['area'] = 'Areas';
$GLOBALS['TL_LANG']['MSC']['distances'] = 'Distances';
$GLOBALS['TL_LANG']['MSC']['other'] = 'Other';
$GLOBALS['TL_LANG']['MSC']['sampleOutputs'] = 'Sample outputs';
$GLOBALS['TL_LANG']['MSC']['contact'] = 'Contact';
$GLOBALS['TL_LANG']['MSC']['moreObjectImages'] = 'More object images';
$GLOBALS['TL_LANG']['MSC']['documents'] = 'Documents';
$GLOBALS['TL_LANG']['MSC']['map'] = 'Map';
$GLOBALS['TL_LANG']['MSC']['objectAddress'] = 'Object address';
$GLOBALS['TL_LANG']['MSC']['yes'] = 'Yes';
$GLOBALS['TL_LANG']['MSC']['no'] = 'No';
$GLOBALS['TL_LANG']['MSC']['energyCertificate'] = 'Energy certificate';
$GLOBALS['TL_LANG']['MSC']['energyDemandValue'] = 'Energy demand value';
$GLOBALS['TL_LANG']['MSC']['energyConsumptionIndex'] = 'Energy consumption index';
$GLOBALS['TL_LANG']['MSC']['filterReset'] = 'Reset filter';
$GLOBALS['TL_LANG']['MSC']['searchEstate'] = 'Search real estate';
$GLOBALS['TL_LANG']['MSC']['all'] = 'All';
$GLOBALS['TL_LANG']['MSC']['objectDetails'] = 'Object details';
$GLOBALS['TL_LANG']['MSC']['placeholderImage'] = 'Placeholder image';
$GLOBALS['TL_LANG']['MSC']['notNecessary'] = 'Not necessary';

$GLOBALS['TL_LANG']['CTE']['makler_headerImage'] = ['MaklerModul header image', 'MaklerModul header image'];
$GLOBALS['TL_LANG']['FMD']['immoHeaderImageView'] = ['MaklerModul header image', 'MaklerModul header image'];

$GLOBALS['TL_LANG']['MOD']['makler_modul_errors'] = [];
$GLOBALS['TL_LANG']['MOD']['makler_modul_errors']['no_detail_page'] = 'Please select a detail page in the Real Estate List module.';
$GLOBALS['TL_LANG']['MOD']['makler_modul_errors']['has-no-objects'] = '<p>There are no objects available at the moment.</p>';

include_once 'makler_modul_mplus.php';
