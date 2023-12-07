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

    'version' => '0.2',

    /*
    |--------------------------------------------------------------------------
    | Version of ELF CMS Basic
    |--------------------------------------------------------------------------
    |
    | Minimum version of ELF CMS Basic package
    |
    */

    'basic_package' => '1.4.3',

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
            "icon" => "/vendor/elfcms/infobox/admin/images/icons/box.png",
            "position" => 100,
            "submenu" => [
                [
                    "title" => "Navigation",
                    "lang_title" => "infobox::default.navigation",
                    "route" => "admin.infobox.nav"
                ],
                [
                    "title" => "Infoboxes",
                    "lang_title" => "infobox::default.infoboxes",
                    "route" => "admin.infobox.infoboxes"
                ],
                [
                    "title" => "Categories",
                    "lang_title" => "infobox::default.categories",
                    "route" => "admin.infobox.categories"
                ],
                [
                    "title" => "Items",
                    "lang_title" => "infobox::default.items",
                    "route" => "admin.infobox.items"
                ],
            ]
        ],
    ],

    'components' => [
        'box' => [
            'class' => '\Elfcms\Infobox\View\Components\Box',
            'options' => [
                'item' => false,
                'theme' => 'default',
            ],
        ],
    ],
];
