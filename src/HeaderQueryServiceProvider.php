<?php

namespace SvenNoorlander\HeaderQuery;

use Illuminate\Support\ServiceProvider;

class HeaderQueryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
    	$this->publishes([
    			__DIR__.'/config/header-query.php' => config_path('header-query.php'),
    	]);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
    	$this->mergeConfigFrom(
    			__DIR__.'/config/header-query.php', 'header-query'
		);
    }
}
