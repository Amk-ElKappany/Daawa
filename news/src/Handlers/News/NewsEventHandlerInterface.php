<?php namespace ButterflyEffect\News\Handlers\News;

use ButterflyEffect\News\Models\News;
use Cartalyst\Support\Handlers\EventHandlerInterface as BaseEventHandlerInterface;

interface NewsEventHandlerInterface extends BaseEventHandlerInterface {

	/**
	 * When a news is being created.
	 *
	 * @param  array  $data
	 * @return mixed
	 */
	public function creating(array $data);

	/**
	 * When a news is created.
	 *
	 * @param  \ButterflyEffect\News\Models\News  $news
	 * @return mixed
	 */
	public function created(News $news);

	/**
	 * When a news is being updated.
	 *
	 * @param  \ButterflyEffect\News\Models\News  $news
	 * @param  array  $data
	 * @return mixed
	 */
	public function updating(News $news, array $data);

	/**
	 * When a news is updated.
	 *
	 * @param  \ButterflyEffect\News\Models\News  $news
	 * @return mixed
	 */
	public function updated(News $news);

	/**
	 * When a news is being deleted.
	 *
	 * @param  \ButterflyEffect\News\Models\News  $news
	 * @return mixed
	 */
	public function deleting(News $news);

	/**
	 * When a news is deleted.
	 *
	 * @param  \ButterflyEffect\News\Models\News  $news
	 * @return mixed
	 */
	public function deleted(News $news);

}
