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
 * Backend modules
 */
if (!is_array($GLOBALS['BE_MOD']['pdir'])) {
    array_insert($GLOBALS['BE_MOD'], 1, array('pdir' => array()));
}

$assetsDir = 'bundles/pdirmaklermodul';

array_insert($GLOBALS['BE_MOD']['pdir'], 0, array(
    'maklermodulSetup' => array(
        'callback'          => 'Pdir\MaklermodulBundle\Module\MaklermodulSetup',
        'icon'              => $assetsDir . '/img/icon.png',
        // 'javascript'        =>  $assetsDir . '/js/backend.min.js',
        // 'stylesheet'		=>  $assetsDir . '/css/backend.css'
    ),
));

/**
 * Frontend modules
 */
$GLOBALS['FE_MOD']['pdirMaklermodul'] = array(
    'immoListView' => 'Pdir\MaklermodulBundle\Module\ListView',
    'immoDetailView' => 'Pdir\MaklermodulBundle\Module\DetailView',
    'immoHeaderImageView' => 'Pdir\MaklermodulBundle\Module\HeaderImageView'
);

$GLOBALS['TL_CTE']['pdirMaklermodul'] = array(
    'makler_headerImage' => 'Pdir\MaklermodulBundle\Module\HeaderImageView'
);

/**
 * Hooks
 */
// $GLOBALS['TL_HOOKS']['getPageIdFromUrl'][]    	= array('MaklerModulMplus\DetailViewHooks', 'hookGetPageIdFromUrl');
$GLOBALS['TL_HOOKS']['getSearchablePages'][]	    = array('Pdir\MaklermodulBundle\Util\Helper', 'addProductsToSearchIndex');
$GLOBALS['TL_HOOKS']['parseTemplate'][]             = array('Pdir\MaklermodulBundle\Util\Helper', 'addListPagination');
// $GLOBALS['TL_HOOKS']['generateBreadcrumb'][]     = array('MaklerModulMplus\Helper', 'addProductToBreadcrumb');
// $GLOBALS['TL_HOOKS']['parseFrontendTemplate'][]  = array('MaklerModulMplus\Helper', 'parseOpenImmoFeedbackTemplate');
$GLOBALS['TL_HOOKS']['addPrivacyWidget'][]          = array('Pdir\MaklermodulBundle\Util\Helper', 'addPrivacyWidget');

/**
 * auto items
 */
$GLOBALS['TL_AUTO_ITEM'][] = 'estate';

/**
 * Javascript & Stylesheet for Backend
 */
if (TL_MODE == 'BE')
{
    if (!is_array($GLOBALS['TL_JAVASCRIPT']))
    {
        $GLOBALS['TL_JAVASCRIPT'] = [];
    }

    $GLOBALS['TL_JAVASCRIPT'][] = 'assets/jquery/js/jquery.min.js';
    $GLOBALS['TL_JAVASCRIPT'][] =  $assetsDir . '/js/backend.js';
    $GLOBALS['TL_CSS'][] =  $assetsDir . '/css/maklermodul_backend.scss||static';
}
