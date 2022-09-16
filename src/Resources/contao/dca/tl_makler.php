<?php

use Pdir\MaklermodulBundle\EventListener\DataContainerListener;

$tableName = 'tl_makler';

$GLOBALS['TL_DCA'][$tableName] = [
    // Config
    'config' => [
        'dataContainer'               => 'Table',
        'sql' => [
            'keys' => [
                'id' => 'primary',
                'alias' => 'index',
            ]
        ],
        'ondelete_callback' => [
            [DataContainerListener::class, 'deleteObject']
        ],
        'onsubmit_callback' => [
            [DataContainerListener::class, 'saveObject']
        ]
    ],

    // List
    'list' => [
        'sorting' => [
            'mode'              => 2,
            'fields'            => ['extern', 'name', 'tstamp', 'lastUpdate'],
            'panelLayout'       => 'sort,filter;search,limit',
            'headerFields'      => ['extern', 'name', 'tstamp', 'lastUpdate'],
            'paste_button_callback' => [DataContainerListener::class, 'renderToolbar']
        ],
        'label' => [
            'fields'            => ['extern', 'name', 'tstamp', 'lastUpdate'],
            'showColumns'      => true,
            'format'            => '<span style="color:#999;padding-right:3px">[%s]</span> %s, %s, %s',
        ],
        'global_operations' => [
            'all' => [
                'href'          => 'act=select',
                'class'         => 'header_edit_all',
                'attributes'    => 'onclick="Backend.getScrollOffset()" accesskey="e"'
            ],
            'toolbar' => [
                'href'                => 'act=renderToolbar',
                'class'               => 'renderToolbar',
                'button_callback'     => [DataContainerListener::class, 'renderToolbar']
            ]
        ],
        'operations' => [
            'edit' => [
                'href'          => 'act=edit',
                'icon'          => 'edit.svg',
            ],
            'delete' => [
                'href'          => 'act=delete',
                'icon'          => 'delete.svg',
                'attributes'    => 'onclick="if(!confirm(\'' . ($GLOBALS['TL_LANG']['MSC']['deleteConfirm'] ?? null) . '\'))return false;Backend.getScrollOffset()"',
                // 'button_callback'     => [DataContainerListener::class, 'deleteObject']
            ],
            /*
            'visible' => [
                'icon'          => 'visible.svg',
                'attributes'    => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
                #'button_callback'     => [$tableName, 'toggleIcon']
            ],*/
            'show' => [
                'href'          => 'act=show',
                'icon'          => 'show.svg'
            ]
        ],
    ],

    // Palettes
    'palettes' => [
        '__selector__'  => [],
        'default'       => '{makler_legend},name,alias,anid,obid,intern,extern,tstamp,lastUpdate,visible;'
    ],

    // Subpalettes
    'subpalettes' => [
    ],

    // Fields
    'fields' => [
        'id' => [
            'sql'       => "int(10) unsigned NOT NULL auto_increment"
        ],
        'tstamp' => [
            'sql'       => "int(10) unsigned NOT NULL default 0",
            'sorting'   => true,
        ],
        'name' => [
            'label'     => &$GLOBALS['TL_LANG'][$tableName]['name'],
            'exclude'   => true,
            'sorting'   => true,
            # 'filter'    => true,
            'search'    => true,
            'inputType' => 'text',
            'eval'      => ['maxlength' => 255, 'tl_class'=>'w50'],
            'sql'       => "varchar(255) NOT NULL default ''"
        ],
        'alias' => [
            'exclude'                 => true,
            'search'                  => true,
            'inputType'               => 'text',
            'eval'                    => ['rgxp'=>'folderalias', 'doNotCopy'=>true, 'maxlength'=>255, 'tl_class'=>'w50'],
            'sql'                     => "varchar(255) BINARY NOT NULL default ''"
        ],
        'anid' => [
            'label'     => &$GLOBALS['TL_LANG'][$tableName]['anid'],
            'exclude'   => true,
            'inputType' => 'text',
            'sorting'   => true,
            'filter'    => true,
            'search'    => true,
            'eval'      => ['tl_class'=>'w50 clr'],
            'sql'       => "text() NULL default"
        ],
        'obid' => [
            'label'     => &$GLOBALS['TL_LANG'][$tableName]['obid'],
            'exclude'   => true,
            'inputType' => 'text',
            'sorting'   => true,
            'filter'    => true,
            'search'    => true,
            'eval'      => ['tl_class'=>'w50'],
            'sql'       => "text() NOT NULL default ''"
        ],
        'intern' => [
            'label'     => &$GLOBALS['TL_LANG'][$tableName]['intern'],
            'exclude'   => true,
            'inputType' => 'text',
            'sorting'   => true,
            'filter'    => true,
            'search'    => true,
            'eval'      => ['tl_class'=>'w50'],
            'sql'       => "text() NOT NULL default ''"
        ],
        'extern' => [
            'label'     => &$GLOBALS['TL_LANG'][$tableName]['extern'],
            'exclude'   => true,
            'inputType' => 'text',
            'sorting'   => true,
            'filter'    => true,
            'search'    => true,
            'eval'      => ['tl_class'=>'w50'],
            'sql'       => "text() NOT NULL default ''"
        ],
        'visible' => [
            'label'     => &$GLOBALS['TL_LANG'][$tableName]['visible'],
            'exclude'   => true,
            'inputType' => 'checkbox',
            'eval'      => ['tl_class'=>'w50 clr'],
            'sql'       => "char(1) NOT NULL default '1'"
        ],
        'lastUpdate' => [
            'label'     => &$GLOBALS['TL_LANG'][$tableName]['lastUpdate'],
            'exclude'   => true,
            'inputType' => 'datetime',
            'sorting'   => true,
            'eval'      => ['rgxp'=>'datim', 'tl_class'=>'w50'],
            'sql'       => "varchar(10) NOT NULL default ''",
        ],
    ]
];
