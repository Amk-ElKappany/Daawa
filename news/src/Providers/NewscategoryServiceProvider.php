<?php namespace ButterflyEffect\News\Providers;

use Cartalyst\Support\ServiceProvider;

class NewscategoryServiceProvider extends ServiceProvider {

	/**
	 * {@inheritDoc}
	 */
	public function boot()
	{
		// Register the attributes namespace
		$this->app['platform.attributes.manager']->registerNamespace(
			$this->app['ButterflyEffect\News\Models\Newscategory']
		);

		// Subscribe the registered event handler
		$this->app['events']->subscribe('butterfly-effect.news.newscategory.handler.event');
	}

	/**
	 * {@inheritDoc}
	 */
	public function register()
	{
		// Register the repository
		$this->bindIf('butterfly-effect.news.newscategory', 'ButterflyEffect\News\Repositories\Newscategory\NewscategoryRepository');

		// Register the data handler
		$this->bindIf('butterfly-effect.news.newscategory.handler.data', 'ButterflyEffect\News\Handlers\Newscategory\NewscategoryDataHandler');

		// Register the event handler
		$this->bindIf('butterfly-effect.news.newscategory.handler.event', 'ButterflyEffect\News\Handlers\Newscategory\NewscategoryEventHandler');

		// Register the validator
		$this->bindIf('butterfly-effect.news.newscategory.validator', 'ButterflyEffect\News\Validator\Newscategory\NewscategoryValidator');
	}

}
