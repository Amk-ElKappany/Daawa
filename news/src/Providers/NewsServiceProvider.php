<?php namespace ButterflyEffect\News\Providers;

use Cartalyst\Support\ServiceProvider;

class NewsServiceProvider extends ServiceProvider {

	/**
	 * {@inheritDoc}
	 */
	public function boot()
	{
		// Register the attributes namespace
		$this->app['platform.attributes.manager']->registerNamespace(
			$this->app['ButterflyEffect\News\Models\News']
		);

		// Subscribe the registered event handler
		$this->app['events']->subscribe('butterfly-effect.news.news.handler.event');
	}

	/**
	 * {@inheritDoc}
	 */
	public function register()
	{
		// Register the repository
		$this->bindIf('butterfly-effect.news.news', 'ButterflyEffect\News\Repositories\News\NewsRepository');

		// Register the data handler
		$this->bindIf('butterfly-effect.news.news.handler.data', 'ButterflyEffect\News\Handlers\News\NewsDataHandler');

		// Register the event handler
		$this->bindIf('butterfly-effect.news.news.handler.event', 'ButterflyEffect\News\Handlers\News\NewsEventHandler');

		// Register the validator
		$this->bindIf('butterfly-effect.news.news.validator', 'ButterflyEffect\News\Validator\News\NewsValidator');
	}

}
