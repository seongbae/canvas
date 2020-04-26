<?php

namespace Seongbae\Canvas\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

class PageServiceProvider extends ServiceProvider
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
        \Route::get('{slug}', [
            'uses' => '\Seongbae\Canvas\Http\Controllers\PageController@getPage' 
        ])->where('slug', '^(?!admin).*$');
    }

}
