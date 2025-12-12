<?php

namespace App\Providers;

use App\ViewComposers\MenuComposer;
use App\ViewComposers\ProfileComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer(
            ['*'],
            ProfileComposer::class
        );

        View::composer(
            ['layouts.menu'],
            MenuComposer::class
        );
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
