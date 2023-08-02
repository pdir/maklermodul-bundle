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

use Pdir\MaklermodulBundle\EventListener\DataContainerListener;

$tableName = 'tl_makler';

$GLOBALS['TL_DCA'][$tableName] = [
    // Config
    'config' => [
        'dataContainer' => 'Table',
        'sql' => [
            'keys' => [
                'id' => 'primary',
                'alias' => 'index',
            ],
        ],
        'ondelete_callback' => [
            [DataContainerListener::class, 'deleteObject'],
        ],
        'onsubmit_callback' => [
            [DataContainerListener::class, 'saveObject'],
        ],
    ],

    // List
    'list' => [
        'sorting' => [
            'mode' => 2,
            'fields' => ['extern', 'name', 'tstamp', 'lastUpdate'],
            'panelLayout' => 'sort,filter;search,limit',
            'headerFields' => ['extern', 'name', 'tstamp', 'lastUpdate'],
            'paste_button_callback' => [DataContainerListener::class, 'renderToolbar'],
        ],
        'label' => [
            'fields' => ['extern', 'name', 'tstamp', 'lastUpdate'],
            'showColumns' => true
        ],
        'global_operations' => [
            'all' => [
                'href' => 'act=select',
                'class' => 'header_edit_all',
                'attributes' => 'onclick="Backend.getScrollOffset()" accesskey="e"',
            ],
            'toolbar' => [
                'href' => 'act=renderToolbar',
                'class' => 'renderToolbar',
                'button_callback' => [DataContainerListener::class, 'renderToolbar'],
            ],
        ],
        'operations' => [
            'edit' => [
                'href' => 'act=edit',
                'icon' => 'edit.svg',
            ],
            'delete' => [
                'href' => 'act=delete',
                'icon' => 'delete.svg',
                'attributes' => 'onclick="if(!confirm(\''.($GLOBALS['TL_LANG']['MSC']['deleteConfirm'] ?? null).'\'))return false;Backend.getScrollOffset()"',
                // 'button_callback'     => [DataContainerListener::class, 'deleteObject']
            ],
            /*
            'visible' => [
                'icon'          => 'visible.svg',
                'attributes'    => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
                #'button_callback'     => [$tableName, 'toggleIcon']
            ],*/
            'show' => [
                'href' => 'act=show',
                'icon' => 'show.svg',
            ],
        ],
    ],

    // Palettes
    'palettes' => [
        '__selector__' => [],
        'default' => '{makler_legend},name,alias,anid,obid,intern,extern,tstamp,lastUpdate,visible;',
    ],

    // Subpalettes
    'subpalettes' => [
    ],

    // Fields
    'fields' => [
        'id' => [
            'sql' => 'int(10) unsigned NOT NULL auto_increment',
        ],
        'tstamp' => [
            'sql' => 'int(10) unsigned NOT NULL default 0',
            'sorting' => true,
        ],
        'name' => [
            'label' => &$GLOBALS['TL_LANG'][$tableName]['name'],
            'exclude' => true,
            'sorting' => true,
            // 'filter'    => true,
            'search' => true,
            'inputType' => 'text',
            'eval' => ['maxlength' => 255, 'tl_class' => 'w50'],
            'sql' => "varchar(255) NOT NULL default ''",
        ],
        'alias' => [
            'exclude' => true,
            'search' => true,
            'inputType' => 'text',
            'eval' => ['rgxp' => 'folderalias', 'doNotCopy' => true, 'maxlength' => 255, 'tl_class' => 'w50'],
            'sql' => "varchar(255) BINARY NOT NULL default ''",
        ],
        'anid' => [
            'label' => &$GLOBALS['TL_LANG'][$tableName]['anid'],
            'exclude' => true,
            'inputType' => 'text',
            'sorting' => true,
            'filter' => true,
            'search' => true,
            'eval' => ['tl_class' => 'w50 clr'],
            'sql' => 'text NULL',
        ],
        'obid' => [
            'label' => &$GLOBALS['TL_LANG'][$tableName]['obid'],
            'exclude' => true,
            'inputType' => 'text',
            'sorting' => true,
            'filter' => true,
            'search' => true,
            'eval' => ['tl_class' => 'w50'],
            'sql' => "text NULL",
        ],
        'intern' => [
            'label' => &$GLOBALS['TL_LANG'][$tableName]['intern'],
            'exclude' => true,
            'inputType' => 'text',
            'sorting' => true,
            'filter' => true,
            'search' => true,
            'eval' => ['tl_class' => 'w50'],
            'sql' => "text NULL",
        ],
        'extern' => [
            'label' => &$GLOBALS['TL_LANG'][$tableName]['extern'],
            'exclude' => true,
            'inputType' => 'text',
            'sorting' => true,
            'filter' => true,
            'search' => true,
            'eval' => ['tl_class' => 'w50'],
            'sql' => "text NULL",
        ],
        'visible' => [
            'label' => &$GLOBALS['TL_LANG'][$tableName]['visible'],
            'exclude' => true,
            'inputType' => 'checkbox',
            'eval' => ['tl_class' => 'w50 clr'],
            'sql' => "char(1) NOT NULL default '1'",
        ],
        'lastUpdate' => [
            'label' => &$GLOBALS['TL_LANG'][$tableName]['lastUpdate'],
            'exclude' => true,
            'inputType' => 'datetime',
            'sorting' => true,
            'eval' => ['rgxp' => 'datim', 'tl_class' => 'w50'],
            'sql' => "varchar(10) NOT NULL default ''",
        ],
    ],
];
