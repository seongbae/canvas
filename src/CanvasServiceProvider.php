<?php

namespace Seongbae\Canvas;

use Illuminate\Support\ServiceProvider;
use Illuminate\View\View;
use Blade;
use Illuminate\Support\Facades\Log;
use Schema;
use Seongbae\Canvas\Console\Commands\CanvasInstallCommand;
use Seongbae\Canvas\Console\Commands\CacheClearCommand;
use Seongbae\Canvas\Console\Commands\GeneratesCrud;
use Spatie\Permission\Commands\CacheReset;
use DB;
use Seongbae\Canvas\Components\Checkbox;
use Seongbae\Canvas\Components\Checkboxes;
use Seongbae\Canvas\Components\File;
use Seongbae\Canvas\Components\Input;
use Seongbae\Canvas\Components\Radios;
use Seongbae\Canvas\Components\Select;
use Seongbae\Canvas\Components\Textarea;
use Spatie\Flash\Flash;

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
        $this->publishes([__DIR__ . '/../resources/vendor' => resource_path('views/vendor')], ['canvas-install', 'canvas-vendor-views']);

        // Seeder stubs
        //$this->publishes([__DIR__ . '/../database/seeds' => database_path('seeds')], ['canvas-install', 'canvas-seeds']);

        // Routes stubs
        $this->publishes([__DIR__ . '/../resources/stubs/routes/routes.stub' => base_path('routes/web.php')], ['canvas-install']);

        
        $this->mergeConfigFrom(__DIR__.'/../config/activitylog.php', 'activitylog');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'canvas');
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php', 'canvas');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');;

        if ($this->app->runningInConsole()) {
            $this->commands(CanvasInstallCommand::class);
            $this->commands(GeneratesCrud::class);
        }

        //

        // Do not run below using initial installation when DB is not available   
        if (file_exists(base_path('.env')))// 
        {
            $pdo = DB::connection()->getPdo();

            if ($pdo && Schema::hasTable('options'))
            {
                // Set config values from database
                config(['mail.from.name' => option('from_name')]);
                config(['mail.from.address' => option('from_email')]);

                $modulesArray = json_decode(option('modules'), true);
                $moduleMenus = array();
                if ($modulesArray != null)
                {
                    foreach ($modulesArray as $module=>$value)
                    {
                        if ($value == 2) // 0 = uninstalled, 1 = installed but not active, 2 = installed and active
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

                // for loading page routes dynamically
                \App::register('Seongbae\Canvas\Providers\PageServiceProvider');

                // Disable theme support for now
                // $theme = option('theme');

                // view()->composer('*', function ($view) use($theme) {
                //     $view->with('theme',  $theme);
                // });

                // $paths = config('view.paths');
                // array_unshift($paths, resource_path('views/themes/'.$theme));
                // config(["view.paths" => $paths ]);
            }

        }

        $this->loadViewComponentsAs('canvas', [
            Checkbox::class,
            Checkboxes::class,
            File::class,
            Input::class,
            Radios::class,
            Select::class,
            Textarea::class,
        ]);

         Flash::levels([
            'success' => 'alert-success',
            'warning' => 'alert-warning',
            'error' => 'alert-error',
        ]);
    }
}
