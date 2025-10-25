<?php

namespace Elfcms\Infobox\Providers;

use Elfcms\Infobox\Models\InfoboxDataType;
use Elfcms\Elfcms\Http\Middleware\AccountUser;
use Elfcms\Elfcms\Http\Middleware\AdminUser;
use Elfcms\Elfcms\Http\Middleware\CookieCheck;
use Elfcms\Infobox\Console\Commands\ElfcmsInfobox;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class ElfcmsModuleProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $moduleDir = dirname(__DIR__);

        $locales = config('elfcms.elfcms.locales');

        if ($this->app->runningInConsole()) {
            $this->commands([
                ElfcmsInfobox::class,
            ]);
        }

        $this->loadRoutesFrom($moduleDir.'/routes/web.php');
        $this->loadViewsFrom($moduleDir.'/resources/views', 'elfcms');
        $this->loadMigrationsFrom($moduleDir.'/database/migrations');

        $this->loadTranslationsFrom($moduleDir.'/resources/lang', 'infobox');

        if (!empty($locales) && is_array($locales)) {
            foreach ($locales as $locale) {
                if (!empty($locale['code'])) {
                    $this->publishes([
                        $moduleDir.'/resources/lang/'.$locale['code'].'/validation.php' => resource_path('lang/'.$locale['code'].'/validation.php'),
                    ],'lang');
                }
            }
        }

        $this->publishes([
            $moduleDir.'/resources/lang' => resource_path('lang/elfcms/infobox'),
        ],'lang');

        $this->publishes([
            $moduleDir.'/config/infobox.php' => config_path('elfcms/infobox.php'),
        ],'config');

        $this->publishes([
            $moduleDir.'/resources/views/admin' => resource_path('views/elfcms/admin'),
        ],'admin');
        $this->publishes([
            $moduleDir.'/public/admin' => public_path('elfcms/admin/modules/infobox/'),
        ], 'admin');

        $this->publishes([
            $moduleDir.'/resources/views/public' => resource_path('views/elfcms/public/infobox'),
        ],'public');

        $this->publishes([
            $moduleDir.'/resources/views/components' => resource_path('views/elfcms/components'),
        ],'components');

        $this->publishes([
            $moduleDir.'/resources/views/emails' => resource_path('views/elfcms/emails'),
        ],'emails');

        Blade::component('infobox-infobox', \Elfcms\Infobox\View\Components\Infobox::class);
        Blade::component('infobox-categories', \Elfcms\Infobox\View\Components\Categories::class);
        Blade::component('infobox-category', \Elfcms\Infobox\View\Components\Category::class);
    }
}
