<?php namespace ButterflyEffect\News\Handlers\Newscategory;

use ButterflyEffect\News\Models\Newscategory;
use Cartalyst\Support\Handlers\EventHandlerInterface as BaseEventHandlerInterface;

interface NewscategoryEventHandlerInterface extends BaseEventHandlerInterface {

	/**
	 * When a newscategory is being created.
	 *
	 * @param  array  $data
	 * @return mixed
	 */
	public function creating(array $data);

	/**
	 * When a newscategory is created.
	 *
	 * @param  \ButterflyEffect\News\Models\Newscategory  $newscategory
	 * @return mixed
	 */
	public function created(Newscategory $newscategory);

	/**
	 * When a newscategory is being updated.
	 *
	 * @param  \ButterflyEffect\News\Models\Newscategory  $newscategory
	 * @param  array  $data
	 * @return mixed
	 */
	public function updating(Newscategory $newscategory, array $data);

	/**
	 * When a newscategory is updated.
	 *
	 * @param  \ButterflyEffect\News\Models\Newscategory  $newscategory
	 * @return mixed
	 */
	public function updated(Newscategory $newscategory);

	/**
	 * When a newscategory is being deleted.
	 *
	 * @param  \ButterflyEffect\News\Models\Newscategory  $newscategory
	 * @return mixed
	 */
	public function deleting(Newscategory $newscategory);

	/**
	 * When a newscategory is deleted.
	 *
	 * @param  \ButterflyEffect\News\Models\Newscategory  $newscategory
	 * @return mixed
	 */
	public function deleted(Newscategory $newscategory);

}
