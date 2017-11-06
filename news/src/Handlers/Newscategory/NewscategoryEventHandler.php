<?php namespace ButterflyEffect\News\Handlers\Newscategory;

use Illuminate\Events\Dispatcher;
use ButterflyEffect\News\Models\Newscategory;
use Cartalyst\Support\Handlers\EventHandler as BaseEventHandler;

class NewscategoryEventHandler extends BaseEventHandler implements NewscategoryEventHandlerInterface {

	/**
	 * {@inheritDoc}
	 */
	public function subscribe(Dispatcher $dispatcher)
	{
		$dispatcher->listen('butterfly-effect.news.newscategory.creating', __CLASS__.'@creating');
		$dispatcher->listen('butterfly-effect.news.newscategory.created', __CLASS__.'@created');

		$dispatcher->listen('butterfly-effect.news.newscategory.updating', __CLASS__.'@updating');
		$dispatcher->listen('butterfly-effect.news.newscategory.updated', __CLASS__.'@updated');

		$dispatcher->listen('butterfly-effect.news.newscategory.deleted', __CLASS__.'@deleting');
		$dispatcher->listen('butterfly-effect.news.newscategory.deleted', __CLASS__.'@deleted');
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
	public function created(Newscategory $newscategory)
	{
		$this->flushCache($newscategory);
	}

	/**
	 * {@inheritDoc}
	 */
	public function updating(Newscategory $newscategory, array $data)
	{

	}

	/**
	 * {@inheritDoc}
	 */
	public function updated(Newscategory $newscategory)
	{
		$this->flushCache($newscategory);
	}

	/**
	 * {@inheritDoc}
	 */
	public function deleting(Newscategory $newscategory)
	{

	}

	/**
	 * {@inheritDoc}
	 */
	public function deleted(Newscategory $newscategory)
	{
		$this->flushCache($newscategory);
	}

	/**
	 * Flush the cache.
	 *
	 * @param  \ButterflyEffect\News\Models\Newscategory  $newscategory
	 * @return void
	 */
	protected function flushCache(Newscategory $newscategory)
	{
		$this->app['cache']->forget('butterfly-effect.news.newscategory.all');

		$this->app['cache']->forget('butterfly-effect.news.newscategory.'.$newscategory->id);
	}

}
