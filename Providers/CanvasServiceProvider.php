<?php

namespace App\Canvas\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\View\View;
use Blade;
use Illuminate\Support\Facades\Log;
use Schema;

class CanvasServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->loadCanvas();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Do not run below using initial installation when DB is not available   
        if (Schema::hasTable('options'))
        {
            $this->loadViewsFrom(__DIR__.'/../views', 'canvas');

            $theme = option('theme');

            view()->composer('*', function ($view) use($theme) {
                $view->with('theme',  $theme);
            });

            $paths = config('view.paths');
            array_unshift($paths, resource_path('views/themes/'.$theme));
            config(["view.paths" => $paths ]);

            config(['mail.from.name' => option('from_name')]);
            config(['mail.from.address' => option('from_email')]);

            $modulesArray = json_decode(option('modules'), true);
            $moduleMenus = array();
            if ($modulesArray != null)
            {
                foreach ($modulesArray as $module=>$value)
                {
                    if ($value == 2)
                    {
                        app('config')->set('custom', require app_path('Modules/'.$module.'/module.php'));

                        \App::register('App\Modules\\'.$module.'\\'.config('custom.service_provider'));

                        $moduleMenus[] = config('custom.admin_menus');
                    }
                }
            }

            view()->composer('*', function ($view) use($moduleMenus) {
                $view->with('moduleMenus',  $moduleMenus);
            });

            \Route::get('{slug}', [
                'uses' => '\App\Canvas\Http\Controllers\PageController@getPage' 
            ])->where('slug', '^(?!api).*$');

            // This route is for loading dynamic pages as part of loading last in orders
            //$this->loadRoutesFrom(__DIR__.'/../Routes/web.php');
            //\App::register('App\Canvas\Providers\PageServiceProvider');

        }
    }

    protected function loadCanvas()
    {
        foreach (glob(__DIR__.'/../*.php') as $filename) {
            require_once $filename;
        }

        foreach (glob(__DIR__.'/../Controllers/*.php') as $filename) {
            require_once $filename;
        }

        foreach (glob(__DIR__.'/../Controllers/Admin/*.php') as $filename) {
            require_once $filename;
        }

        foreach (glob(__DIR__.'/../DataTables/*.php') as $filename) {
            require_once $filename;
        }
    }
}
