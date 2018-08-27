<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2013 Leo Feyer
 *
 * @package   MaklerModulMplus
 * @author    Mathias Arzberger <develop@pdir.de>
 * @license   All-rights-reserved.
 * @copyright pdir / digital agentur
 */

/**
 * Miscellaneous
 */
$GLOBALS['TL_LANG']['MSC']['totalPages']    = 'Page %s of %s';
$GLOBALS['TL_LANG']['MSC']['points'] = '...';

$GLOBALS['TL_LANG']['CTE']['makler_headerImage'] = array('MaklerModul header image', 'MaklerModul header image');
$GLOBALS['TL_LANG']['FMD']['immoHeaderImageView'] = array('MaklerModul header image', 'MaklerModul header image');

$strErrorDefault = 'Makler Modul Error: ';
$GLOBALS['TL_LANG']['MOD']['makler_modul_mplus']['error']['no_detail_page'] = $strErrorDefault . 'Please select the detail page in your list view.';

include_once('makler_modul_mplus.php');
