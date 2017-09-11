<?php

namespace Ayudat\Seolo;

use Illuminate\Support\ServiceProvider;

class SeoloServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        require_once __DIR__.'/helpers.php';

        $this->loadMigrationsFrom(__DIR__.'/migrations');
        $this->loadViewsFrom(__DIR__.'/views', 'seolo');
        $this->loadTranslationsFrom(__DIR__.'/lang', 'seolo');

        //must be done in webpack.mix.js: mix.sass('resources/assets/sass/seolo.scss', 'public/css');

        $this->publishes([
            __DIR__.'/config.php' => config_path('seolo.php'),

            __DIR__.'/js/festives.js' => public_path('js/seolo/festives.js'),
            __DIR__.'/js/tags.js' => public_path('js/seolo/tags.js'),
            __DIR__.'/js/texts.js' => public_path('js/seolo/texts.js'),
            __DIR__.'/js/alts.js' => public_path('js/seolo/alts.js'),

            __DIR__.'/sass/seolo.scss' => resource_path('assets/sass/seolo.scss'),
            __DIR__.'/sass/festives.scss' => resource_path('assets/sass/seolo/festives.scss'),
            __DIR__.'/sass/tags.scss' => resource_path('assets/sass/seolo/tags.scss'),
            __DIR__.'/sass/texts.scss' => resource_path('assets/sass/seolo/texts.scss'),
            __DIR__.'/sass/alts.scss' => resource_path('assets/sass/seolo/alts.scss'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        include __DIR__.'/routes.php';

        $this->app->make('Ayudat\Seolo\SeoloController');
    }
}
