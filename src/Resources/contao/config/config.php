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

use Contao\ArrayUtil;
use Contao\Combiner;
use Pdir\MaklermodulBundle\EventListener\ParseBackendTemplateListener;
use Pdir\MaklermodulBundle\EventListener\ParseTemplateListener;
use Pdir\MaklermodulBundle\Model\MaklerModel;
use Pdir\MaklermodulBundle\Module\DetailView;
use Pdir\MaklermodulBundle\Module\HeaderImageView;
use Pdir\MaklermodulBundle\Module\ListView;
use Pdir\MaklermodulBundle\Module\MaklermodulSetup;
use Pdir\MaklermodulBundle\Util\Helper;

$assetsDir = 'bundles/pdirmaklermodul';

/**
 * Backend modules.
 */
if (!isset($GLOBALS['BE_MOD']['pdir'])) {
    ArrayUtil::arrayInsert($GLOBALS['BE_MOD'], 1, ['pdir' => []]);
}

ArrayUtil::arrayInsert($GLOBALS['BE_MOD']['pdir'], 0, [
    'maklermodulSetup' => [
        'callback' => MaklermodulSetup::class,
    ],
]);

ArrayUtil::arrayInsert($GLOBALS['BE_MOD']['pdir'], 0, [
    'maklermodul' => [
        'tables' => ['tl_makler'],
    ],
]);

/*
 * Frontend modules
 */
$GLOBALS['FE_MOD']['pdirMaklermodul'] = [
    'immoListView' => ListView::class,
    'immoDetailView' => DetailView::class,
    'immoHeaderImageView' => HeaderImageView::class
];

$GLOBALS['TL_CTE']['pdirMaklermodul'] = [
    'makler_headerImage' => HeaderImageView::class,
];

#-- register Models
$GLOBALS['TL_MODELS']['tl_makler']   = MaklerModel::class;

/*
 * Hooks
 */
$GLOBALS['TL_HOOKS']['getSearchablePages'][] = [Helper::class, 'addProductsToSearchIndex'];
$GLOBALS['TL_HOOKS']['parseTemplate'][] = [Helper::class, 'addListPagination'];
$GLOBALS['TL_HOOKS']['parseFrontendTemplate'][]  = [Helper::class, 'parseOpenImmoFeedbackTemplate'];
$GLOBALS['TL_HOOKS']['addPrivacyWidget'][] = [Helper::class, 'addPrivacyWidget'];

/*
 * auto items
 */
$GLOBALS['TL_AUTO_ITEM'][] = 'estate';

/*
 * Javascript for Backend
 */
if (TL_MODE == 'BE')
{
    if (empty($GLOBALS['TL_JAVASCRIPT']))
    {
        $GLOBALS['TL_JAVASCRIPT'] = [];
    }

    $GLOBALS['TL_JAVASCRIPT'][] =  $assetsDir . '/js/backend.js|static';

    $combiner = new Combiner();
    $combiner->add($assetsDir . '/css/maklermodul_backend.scss');
    $GLOBALS['TL_CSS'][] = str_replace("TL_ASSETS_URL","",$combiner->getCombinedFile());
}
