<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Version
    |--------------------------------------------------------------------------
    |
    | Version of package
    |
    */

    'version' => '1.3.0',

    /*
    |--------------------------------------------------------------------------
    | Version of ELF CMS Basic
    |--------------------------------------------------------------------------
    |
    | Minimum version of ELF CMS Basic package
    |
    */

    'elfcms_package' => '1.7.2',

    /*
    |--------------------------------------------------------------------------
    | Menu data
    |--------------------------------------------------------------------------
    |
    | Menu data of this package for admin panel
    |
    */

    "menu" => [
        [
            "title" => "Infobox",
            "lang_title" => "infobox::default.infobox",
            "route" => "admin.infobox.nav",
            "parent_route" => "admin.infobox",
            "icon" => "/elfcms/admin/modules/infobox/images/icons/box.png",
            "position" => 200,
        ],
    ],

    'components' => [
        'infobox' => [
            'class' => '\Elfcms\Infobox\View\Components\Infobox',
            'options' => [
                'infobox' => false,
                'theme' => 'default',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Access control
    |--------------------------------------------------------------------------
    |
    | Define access rules for admin panel pages.
    |
    */

    "access_routes" => [
        [
            "title" => "Infobox",
            "lang_title" => "infobox::default.infobox",
            "route" => "admin.infobox",
            "actions" => ["read", "write"],
        ],
    ],
];
