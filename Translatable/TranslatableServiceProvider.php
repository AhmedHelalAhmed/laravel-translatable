<?php namespace Dimsav\Translatable;

use Illuminate\Support\ServiceProvider;

class TranslatableServiceProvider extends ServiceProvider {

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
	public function boot() {
		$this->package('dimsav/translatable');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register() {
        $this->app['events']->listen('eloquent.saved*', function($model)
        {
            if ($model instanceof Translatable) {
                $model->saveTranslations();
            }
        });
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides() {
		return array();
	}

}