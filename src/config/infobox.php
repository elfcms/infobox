<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Info
    |--------------------------------------------------------------------------
    |
    | Package info
    |
    */

    'version' => '1.8.0',
    'developer' => 'Maxim Klassen',
    'license' => 'MIT',
    'author' => 'Maxim Klassen',
    'title' => 'Infobox',
    'description' => '',
    'url' => '',
    'github' => 'https://github.com/elfcms/infobox',
    'release_status' => 'stable',
    'release_date' => '2025',

    /*
    |--------------------------------------------------------------------------
    | Version of ELF CMS Basic
    |--------------------------------------------------------------------------
    |
    | Minimum version of ELF CMS Basic package
    |
    */

    'elfcms_package' => '3.0',

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
            "icon" => "/elfcms/admin/modules/infobox/images/icons/box.svg",
            "position" => 200,
            "color" => "dodgerblue",
            "second_color" => "#00becf",

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

    "pages" => [
        'name' => 'Infobox',
        'class' => Elfcms\Infobox\Models\Infobox::class ?? null,
        'search_key' => 'infobox',
        'search_column' => 'slug',
        'options_view' => 'elfcms::admin.infobox.pages.options',
        'router' => Elfcms\Infobox\Services\DynamicPageRouter::class ?? null,
    ],
];
