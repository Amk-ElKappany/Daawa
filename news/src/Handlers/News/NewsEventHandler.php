<?php namespace ButterflyEffect\News\Handlers\News;

use Illuminate\Events\Dispatcher;
use ButterflyEffect\News\Models\News;
use Cartalyst\Support\Handlers\EventHandler as BaseEventHandler;

class NewsEventHandler extends BaseEventHandler implements NewsEventHandlerInterface {

	/**
	 * {@inheritDoc}
	 */
	public function subscribe(Dispatcher $dispatcher)
	{
		$dispatcher->listen('butterfly-effect.news.news.creating', __CLASS__.'@creating');
		$dispatcher->listen('butterfly-effect.news.news.created', __CLASS__.'@created');

		$dispatcher->listen('butterfly-effect.news.news.updating', __CLASS__.'@updating');
		$dispatcher->listen('butterfly-effect.news.news.updated', __CLASS__.'@updated');

		$dispatcher->listen('butterfly-effect.news.news.deleted', __CLASS__.'@deleting');
		$dispatcher->listen('butterfly-effect.news.news.deleted', __CLASS__.'@deleted');
	}

	/**
	 * {@inheritDoc}
	 */
	public function creating(array $data)
	{

	}

	/**
	 * {@inheritDoc}
	 */
	public function created(News $news)
	{
		$this->flushCache($news);
	}

	/**
	 * {@inheritDoc}
	 */
	public function updating(News $news, array $data)
	{

	}

	/**
	 * {@inheritDoc}
	 */
	public function updated(News $news)
	{
		$this->flushCache($news);
	}

	/**
	 * {@inheritDoc}
	 */
	public function deleting(News $news)
	{

	}

	/**
	 * {@inheritDoc}
	 */
	public function deleted(News $news)
	{
		$this->flushCache($news);
	}

	/**
	 * Flush the cache.
	 *
	 * @param  \ButterflyEffect\News\Models\News  $news
	 * @return void
	 */
	protected function flushCache(News $news)
	{
		$this->app['cache']->forget('butterfly-effect.news.news.all');

		$this->app['cache']->forget('butterfly-effect.news.news.'.$news->id);
	}

}
