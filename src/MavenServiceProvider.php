<?php namespace Sukohi\Maven;

use Illuminate\Support\ServiceProvider;

class MavenServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = true;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
        $this->publishes([
            __DIR__.'/translations' => resource_path('lang'),
        ], 'translations');
		$this->publishes([
			__DIR__.'/migrations' => database_path('migrations')
		], 'migrations');
		$this->publishes([
			__DIR__.'/config/maven.php' => config_path('maven.php')
		], 'config');
		$this->publishes([
			__DIR__.'/Controllers/MavenController.php' => app_path('Http/Controllers/Maven/MavenController.php')
		], 'controllers');
		$this->publishes([
			__DIR__.'/views' => resource_path('views/maven')
		], 'views');
		$this->publishes([
			__DIR__.'/Requests' => app_path('Http/Requests')
		], 'requests');

		$this->app->singleton('command.maven:export', function ($app) {

			return $app['Sukohi\Maven\Commands\MavenExportCommand'];

		});
		$this->commands('command.maven:export');
		$this->app->singleton('command.maven:import', function ($app) {

			return $app['Sukohi\Maven\Commands\MavenImportCommand'];

		});
		$this->commands('command.maven:import');

	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['maven'] = $this->app->share(function($app)
		{
			return new Maven;
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return ['maven'];
	}

}