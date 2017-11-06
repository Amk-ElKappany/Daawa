<?php namespace ButterflyEffect\News\Repositories\Newscategory;

use ButterflyEffect\News\Models\News;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Cartalyst\Support\Traits;
use Illuminate\Container\Container;
use Symfony\Component\Finder\Finder;

class NewscategoryRepository implements NewscategoryRepositoryInterface {

	use Traits\ContainerTrait, Traits\EventTrait, Traits\RepositoryTrait, Traits\ValidatorTrait;

	/**
	 * The Data handler.
	 *
	 * @var \ButterflyEffect\News\Handlers\Newscategory\NewscategoryDataHandlerInterface
	 */
	protected $data;

	/**
	 * The Eloquent news model.
	 *
	 * @var string
	 */
	protected $model;

	/**
	 * Constructor.
	 *
	 * @param  \Illuminate\Container\Container  $app
	 * @return void
	 */
	public function __construct(Container $app)
	{
		$this->setContainer($app);

		$this->setDispatcher($app['events']);

		$this->data = $app['butterfly-effect.news.newscategory.handler.data'];

		$this->setValidator($app['butterfly-effect.news.newscategory.validator']);

		$this->setModel(get_class($app['ButterflyEffect\News\Models\Newscategory']));
	}

	/**
	 * {@inheritDoc}
	 */
	public function grid()
	{
		return $this
			->createModel();
	}

	/**
	 * {@inheritDoc}
	 */
	public function findAll()
	{
		return $this->container['cache']->rememberForever('butterfly-effect.news.newscategory.all', function()
		{
			return $this->createModel()->get();
		});
	}

	/**
	 * {@inheritDoc}
	 */
	public function find($id)
	{
		return $this->container['cache']->rememberForever('butterfly-effect.news.newscategory.'.$id, function() use ($id)
		{
			return $this->createModel()->find($id);
		});
	}

	/**
	 * {@inheritDoc}
	 */
	public function validForCreation(array $input)
	{
		return $this->validator->on('create')->validate($input);
	}

	/**
	 * {@inheritDoc}
	 */
	public function validForUpdate($id, array $input)
	{
		return $this->validator->on('update')->validate($input);
	}

	/**
	 * {@inheritDoc}
	 */
	public function store($id, array $input)
	{
		return ! $id ? $this->create($input) : $this->update($id, $input);
	}

	/**
	 * {@inheritDoc}
	 */
	public function create(array $input)
	{
		// Create a new newscategory
		$newscategory = $this->createModel();

		// Fire the 'butterfly-effect.news.newscategory.creating' event
		if ($this->fireEvent('butterfly-effect.news.newscategory.creating', [ $input ]) === false)
		{
			return false;
		}

		// Prepare the submitted data
		$data = $this->data->prepare($input);

		// Validate the submitted data
		$messages = $this->validForCreation($data);

		// Check if the validation returned any errors
		if ($messages->isEmpty())
		{
            if (request()->hasFile('image'))
            {
                $destinationPath =  'storage/news/news-categories';
                $file_info = getimagesize(request()->file('image'));
                if (empty($file_info)) // No Image?
                {
                    $messages->add('image', 'Please, Provide a valid image');
                }
                $file = request()->file('image');
                $attach = $destinationPath . '/' .rand().'-news-categories-' . date("d-m-y-H-M")  . '-' .$file->getClientOriginalName();
                $file->move($destinationPath, ($attach));
                $data['image'] = trim($attach);
            }
            else
                $messages->add('image', 'Please, Provide the the news-categories image');

            if ($messages->isEmpty())
            {
                // Save the newscategory
                $newscategory->fill($data)->save();
                $newscategory->created_by = Sentinel::getUser()->id;
                $newscategory->save();
            }

			// Fire the 'butterfly-effect.news.newscategory.created' event
			$this->fireEvent('butterfly-effect.news.newscategory.created', [ $newscategory ]);
		}

		return [ $messages, $newscategory ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function update($id, array $input)
	{
		// Get the newscategory object
		$newscategory = $this->find($id);

		// Fire the 'butterfly-effect.news.newscategory.updating' event
		if ($this->fireEvent('butterfly-effect.news.newscategory.updating', [ $newscategory, $input ]) === false)
		{
			return false;
		}

		// Prepare the submitted data
		$data = $this->data->prepare($input);

		// Validate the submitted data
		$messages = $this->validForUpdate($newscategory, $data);

		// Check if the validation returned any errors
		if ($messages->isEmpty())
		{
            if (request()->hasFile('image'))
            {
                $destinationPath =  'storage/news/news-categories';
                $file_info = getimagesize(request()->file('image'));
                if (empty($file_info)) // No Image?
                {
                    $messages->add('image', 'Please, Provide a valid image');
                }
                $file = request()->file('image');
                $attach = $destinationPath . '/' .rand().'-news-categories-' . date("d-m-y-H-M")  . '-' .$file->getClientOriginalName();
                $file->move($destinationPath, ($attach));
                $data['image'] = trim($attach);
            }

            if ($messages->isEmpty())
            {
                // Update the newscategory
                $newscategory->fill($data)->save();
                $newscategory->updated_by = Sentinel::getUser()->id;
                $newscategory->save();
            }

			// Fire the 'butterfly-effect.news.newscategory.updated' event
			$this->fireEvent('butterfly-effect.news.newscategory.updated', [ $newscategory ]);
		}

		return [ $messages, $newscategory ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function delete($id)
	{
		// Check if the newscategory exists
		if ($newscategory = $this->find($id))
		{
			// Fire the 'butterfly-effect.news.newscategory.deleting' event
			$this->fireEvent('butterfly-effect.news.newscategory.deleting', [ $newscategory ]);

            if(News::where('newscategory_id', '=', $id)->first() != null)
                return false;

			// Delete the newscategory entry
			$newscategory->delete();

			// Fire the 'butterfly-effect.news.newscategory.deleted' event
			$this->fireEvent('butterfly-effect.news.newscategory.deleted', [ $newscategory ]);

			return true;
		}

		return false;
	}

	/**
	 * {@inheritDoc}
	 */
	public function enable($id)
	{
		$this->validator->bypass();

		return $this->update($id, [ 'enabled' => true ]);
	}

	/**
	 * {@inheritDoc}
	 */
	public function disable($id)
	{
		$this->validator->bypass();

		return $this->update($id, [ 'enabled' => false ]);
	}

}
