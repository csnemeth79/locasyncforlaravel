<?php

namespace Csnemeth79\Locasyncforlaravel;

use Illuminate\Support\ServiceProvider;

class LocasyncforlaravelServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('Csnemeth79/locasyncforlaravel');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
        $this->app['locasyncforlaravel'] = $this->app->share(function() { return new LocasyncforlaravelService; });
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('Locasyncforlaravel');
	}

}