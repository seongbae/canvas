<?php

namespace Seongbae\Canvas;

use Illuminate\Support\ServiceProvider;
use Illuminate\View\View;
use Blade;
use Illuminate\Support\Facades\Log;
use Schema;
use Seongbae\Canvas\Console\Commands\CanvasInstallCommand;
use Seongbae\Canvas\Console\Commands\CacheClearCommand;
use Spatie\Permission\Commands\CacheReset;

class CanvasServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([__DIR__ . '/../public' => public_path('canvas')], ['canvas-install','canvas-public']);

        // Controller stubs
        $this->publishes([__DIR__ . '/../resources/stubs/controllers/auth/ConfirmPasswordController.stub' => app_path('Http/Controllers/Auth/ConfirmPasswordController.php')], 'canvas-install');
        $this->publishes([__DIR__ . '/../resources/stubs/controllers/auth/ForgotPasswordController.stub' => app_path('Http/Controllers/Auth/ForgotPasswordController.php')], 'canvas-install');
        $this->publishes([__DIR__ . '/../resources/stubs/controllers/auth/LoginController.stub' => app_path('Http/Controllers/Auth/LoginController.php')], 'canvas-install');
        $this->publishes([__DIR__ . '/../resources/stubs/controllers/auth/RegisterController.stub' => app_path('Http/Controllers/Auth/RegisterController.php')], 'canvas-install');
        $this->publishes([__DIR__ . '/../resources/stubs/controllers/auth/ResetPasswordController.stub' => app_path('Http/Controllers/Auth/ResetPasswordController.php')], 'canvas-install');
        $this->publishes([__DIR__ . '/../resources/stubs/controllers/auth/VerificationController.stub' => app_path('Http/Controllers/Auth/VerificationController.php')], 'canvas-install');
        $this->publishes([__DIR__ . '/../resources/stubs/controllers/HomeController.stub' => app_path('Http/Controllers/HomeController.php')], 'canvas-install');
        
        // Model stubs
        $this->publishes([__DIR__ . '/../resources/stubs/models/User.stub' => app_path('User.php')], 'canvas-install');

        // View stubs
        $this->publishes([__DIR__ . '/../resources/views' => resource_path('views/vendor/canvas')], ['canvas-views']);
        $this->publishes([__DIR__ . '/../resources/vendor' => resource_path('views/vendor')], ['canvas-vendor-views']);

        // Seeder stubs
        $this->publishes([__DIR__ . '/../database/seeds' => database_path('seeds')], ['canvas-seeds']);

        // Routes stubs
        $this->publishes([__DIR__ . '/../resources/stubs/routes/routes.stub' => base_path('routes/web.php')], ['canvas-install']);

        // DB stubs
        $this->mergeConfigFrom(__DIR__.'/../config/activitylog.php', 'activitylog');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'canvas');
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php', 'canvas');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        //$this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        if ($this->app->runningInConsole()) {
            $this->commands(CanvasInstallCommand::class);
            //$this->commands(CacheClearCommand::class);
            //$this->commands(CacheReset::class);
        }

        // Do not run below using initial installation when DB is not available   
        if (Schema::hasTable('options'))
        {
            $this->loadViewsFrom(__DIR__.'/../views', 'canvas');

            // Disable theme support for now
            // $theme = option('theme');

            // view()->composer('*', function ($view) use($theme) {
            //     $view->with('theme',  $theme);
            // });

            // $paths = config('view.paths');
            // array_unshift($paths, resource_path('views/themes/'.$theme));
            // config(["view.paths" => $paths ]);

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

            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

            \App::register('Seongbae\Canvas\Providers\PageServiceProvider');

        }
    }
}
