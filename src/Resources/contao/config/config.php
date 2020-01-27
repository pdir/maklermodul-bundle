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
 * Backend modules.
 */
if (!is_array($GLOBALS['BE_MOD']['pdir'])) {
    array_insert($GLOBALS['BE_MOD'], 1, ['pdir' => []]);
}

$assetsDir = 'bundles/pdirmaklermodul';

array_insert($GLOBALS['BE_MOD']['pdir'], 0, [
    'maklermodulSetup' => [
        'callback' => 'Pdir\MaklermodulBundle\Module\MaklermodulSetup',
        'icon' => $assetsDir.'/img/icon.png',
        // 'javascript'        =>  $assetsDir . '/js/backend.min.js',
        // 'stylesheet' => $assetsDir.'/css/backend.css',
    ],
]);

/*
 * Frontend modules
 */
$GLOBALS['FE_MOD']['pdirMaklermodul'] = [
    'immoListView' => 'Pdir\MaklermodulBundle\Module\ListView',
    'immoDetailView' => 'Pdir\MaklermodulBundle\Module\DetailView',
    'immoHeaderImageView' => 'Pdir\MaklermodulBundle\Module\HeaderImageView',
];

$GLOBALS['TL_CTE']['pdirMaklermodul'] = [
    'makler_headerImage' => 'Pdir\MaklermodulBundle\Module\HeaderImageView',
];

/*
 * Hooks
 */
$GLOBALS['TL_HOOKS']['getSearchablePages'][] = ['Pdir\MaklermodulBundle\Util\Helper', 'addProductsToSearchIndex'];
$GLOBALS['TL_HOOKS']['parseTemplate'][] = ['Pdir\MaklermodulBundle\Util\Helper', 'addListPagination'];
$GLOBALS['TL_HOOKS']['parseFrontendTemplate'][]  = ['Pdir\MaklermodulBundle\Util\Helper', 'parseOpenImmoFeedbackTemplate'];
$GLOBALS['TL_HOOKS']['addPrivacyWidget'][] = ['Pdir\MaklermodulBundle\Util\Helper', 'addPrivacyWidget'];
// $GLOBALS['TL_HOOKS']['getPageIdFromUrl'][]    	= array('MaklerModulMplus\DetailViewHooks', 'hookGetPageIdFromUrl');
// $GLOBALS['TL_HOOKS']['generateBreadcrumb'][]     = array('MaklerModulMplus\Helper', 'addProductToBreadcrumb');

/*
 * auto items
 */
$GLOBALS['TL_AUTO_ITEM'][] = 'estate';

/*
 * Javascript for Backend
 */
if (TL_MODE == 'BE')
{
    if (!is_array($GLOBALS['TL_JAVASCRIPT']))
    {
        $GLOBALS['TL_JAVASCRIPT'] = [];
    }

    if(!isset($GLOBALS['TL_JAVASCRIPT']['jquery'])) {
        $GLOBALS['TL_JAVASCRIPT']['jquery'] = 'assets/jquery/js/jquery.min.js';
        $GLOBALS['TL_JAVASCRIPT']['noconflict'] = $assetsDir . '/js/jquery.noconflict.js';
    }
    $GLOBALS['TL_JAVASCRIPT'][] =  $assetsDir . '/js/backend.js';

    $combiner = new \Combiner();
    $combiner->add($assetsDir . '/css/maklermodul_backend.scss');
    $GLOBALS['TL_CSS'][] = str_replace("TL_ASSETS_URL","",$combiner->getCombinedFile());
}
