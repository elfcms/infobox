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

    'version' => '1.0',

    /*
    |--------------------------------------------------------------------------
    | Version of ELF CMS Basic
    |--------------------------------------------------------------------------
    |
    | Minimum version of ELF CMS Basic package
    |
    */

    'elfcms_package' => '1.1',

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
