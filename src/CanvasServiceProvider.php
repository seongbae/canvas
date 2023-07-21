<?php

namespace Seongbae\Canvas;

use Illuminate\Support\ServiceProvider;
use Illuminate\View\View;
use Blade;
use Illuminate\Support\Facades\Log;
use Schema;
use Seongbae\Canvas\Console\CanvasInstallCommand;
use Seongbae\Canvas\Console\CacheClearCommand;
use Seongbae\Canvas\Console\GeneratesCrud;
use Spatie\Permission\Commands\CacheReset;
use DB;
use Seongbae\Canvas\Components\Checkbox;
use Seongbae\Canvas\Components\Checkboxes;
use Seongbae\Canvas\Components\File;
use Seongbae\Canvas\Components\Input;
use Seongbae\Canvas\Components\Radios;
use Seongbae\Canvas\Components\Select;
use Seongbae\Canvas\Components\Textarea;
use Seongbae\Canvas\Components\Manymany;
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

//        // Controller stubs
//        $this->publishes([__DIR__ . '/../resources/stubs/controllers/auth/ConfirmPasswordController.stub' => app_path('Http/Controllers/Auth/ConfirmPasswordController.php')], 'canvas-install');
//        $this->publishes([__DIR__ . '/../resources/stubs/controllers/auth/ForgotPasswordController.stub' => app_path('Http/Controllers/Auth/ForgotPasswordController.php')], 'canvas-install');
//        $this->publishes([__DIR__ . '/../resources/stubs/controllers/auth/LoginController.stub' => app_path('Http/Controllers/Auth/LoginController.php')], 'canvas-install');
//        $this->publishes([__DIR__ . '/../resources/stubs/controllers/auth/RegisterController.stub' => app_path('Http/Controllers/Auth/RegisterController.php')], 'canvas-install');
//        $this->publishes([__DIR__ . '/../resources/stubs/controllers/auth/ResetPasswordController.stub' => app_path('Http/Controllers/Auth/ResetPasswordController.php')], 'canvas-install');
//        $this->publishes([__DIR__ . '/../resources/stubs/controllers/auth/VerificationController.stub' => app_path('Http/Controllers/Auth/VerificationController.php')], 'canvas-install');
//        $this->publishes([__DIR__ . '/../resources/stubs/controllers/WelcomeController.stub' => app_path('Http/Controllers/WelcomeController.php')], 'canvas-install');
//        $this->publishes([__DIR__ . '/../resources/stubs/controllers/HomeController.stub' => app_path('Http/Controllers/HomeController.php')], 'canvas-install');
//
//        // Model stubs
//        $this->publishes([__DIR__ . '/../resources/stubs/models/User.stub' => app_path('/Models/User.php')], 'canvas-install');

        // View stubs
//        $this->publishes([__DIR__ . '/../resources/views' => resource_path('views/vendor/canvas')], ['canvas-views']);
//        $this->publishes([__DIR__ . '/../resources/vendor' => resource_path('views/vendor')], ['canvas-install', 'canvas-vendor-views']);

        // Migrations stubs
        $this->publishes([__DIR__ . '/../database/migrations' => database_path('migrations')], ['canvas-install', 'canvas-seeds']);

        // Seeder stubs
        $this->publishes([__DIR__ . '/../database/seeders' => database_path('seeders')], ['canvas-install', 'canvas-seeds']);

        // Routes stubs
        //$this->publishes([__DIR__ . '/../resources/stubs/routes/routes.stub' => base_path('routes/web.php')], ['canvas-install']);

        // Config stubs
        $this->publishes([__DIR__ . '/../config/activitylog.php' => config_path('activitylog.php')], 'config');
        $this->publishes([__DIR__ . '/../config/permission.php' => config_path('permission.php')], 'config');

        $this->mergeConfigFrom(
            __DIR__.'/../config/canvas.php','canvas'
        );

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'canvas');

        if ($this->app->runningInConsole()) {
            $this->commands(CanvasInstallCommand::class);
            $this->commands(GeneratesCrud::class);
        }

        $this->loadRoutesFrom(__DIR__ .  '/../routes/web.php');

        $this->loadViewComponentsAs('canvas', [
            Checkbox::class,
            Checkboxes::class,
            File::class,
            Input::class,
            Radios::class,
            Select::class,
            Textarea::class,
            Manymany::class
        ]);

         Flash::levels([
            'success' => 'alert-success',
            'warning' => 'alert-warning',
            'error' => 'alert-error',
        ]);
    }
}
