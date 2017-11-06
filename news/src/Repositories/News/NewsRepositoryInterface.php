<?php namespace ButterflyEffect\News\Repositories\News;

interface NewsRepositoryInterface {

	/**
	 * Returns a dataset compatible with data grid.
	 *
	 * @return \ButterflyEffect\News\Models\News
	 */
	public function grid();

	/**
	 * Returns all the news entries.
	 *
	 * @return \ButterflyEffect\News\Models\News
	 */
	public function findAll();

	/**
	 * Returns a news entry by its primary key.
	 *
	 * @param  int  $id
	 * @return \ButterflyEffect\News\Models\News
	 */
	public function find($id);

	/**
	 * Determines if the given news is valid for creation.
	 *
	 * @param  array  $data
	 * @return \Illuminate\Support\MessageBag
	 */
	public function validForCreation(array $data);

	/**
	 * Determines if the given news is valid for update.
	 *
	 * @param  int  $id
	 * @param  array  $data
	 * @return \Illuminate\Support\MessageBag
	 */
	public function validForUpdate($id, array $data);

	/**
	 * Creates or updates the given news.
	 *
	 * @param  int  $id
	 * @param  array  $input
	 * @return bool|array
	 */
	public function store($id, array $input);

	/**
	 * Creates a news entry with the given data.
	 *
	 * @param  array  $data
	 * @return \ButterflyEffect\News\Models\News
	 */
	public function create(array $data);

	/**
	 * Updates the news entry with the given data.
	 *
	 * @param  int  $id
	 * @param  array  $data
	 * @return \ButterflyEffect\News\Models\News
	 */
	public function update($id, array $data);

	/**
	 * Deletes the news entry.
	 *
	 * @param  int  $id
	 * @return bool
	 */
	public function delete($id);

}
