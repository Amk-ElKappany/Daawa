<?php namespace ButterflyEffect\News\Repositories\News;

use ButterflyEffect\News\Models\NewsParagraphs;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Cartalyst\Support\Traits;
use Illuminate\Container\Container;
use Symfony\Component\Finder\Finder;

class NewsRepository implements NewsRepositoryInterface {

	use Traits\ContainerTrait, Traits\EventTrait, Traits\RepositoryTrait, Traits\ValidatorTrait;

	/**
	 * The Data handler.
	 *
	 * @var \ButterflyEffect\News\Handlers\News\NewsDataHandlerInterface
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

		$this->data = $app['butterfly-effect.news.news.handler.data'];

		$this->setValidator($app['butterfly-effect.news.news.validator']);

		$this->setModel(get_class($app['ButterflyEffect\News\Models\News']));
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
		return $this->container['cache']->rememberForever('butterfly-effect.news.news.all', function()
		{
			return $this->createModel()->get();
		});
	}

	/**
	 * {@inheritDoc}
	 */
	public function find($id)
	{
		return $this->container['cache']->rememberForever('butterfly-effect.news.news.'.$id, function() use ($id)
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
	    // Create a new news
		$news = $this->createModel();

		// Fire the 'butterfly-effect.news.news.creating' event
		if ($this->fireEvent('butterfly-effect.news.news.creating', [ $input ]) === false)
		{
			return false;
		}

		$system_languages = $input['system_language'];
        $paragraphs = array();
        if($input['counter'] > 0) {
            for ($i = 1; $i <= $input['counter']; $i++) {
                $paragraphs[$i]['title_en'] = $input['paragraph_title_en' . $i];
                foreach ($system_languages as $system_language)
                    $paragraphs[$i]['title_'.$system_language] = $input['paragraph_title_'. $system_language . $i];
                $paragraphs[$i]['description_en'] = $input['paragraph_description_en' . $i];
                foreach ($system_languages as $system_language)
                    $paragraphs[$i]['description_'.$system_language] = $input['paragraph_description_'. $system_language . $i];
                if (request()->hasFile('paragraph_image'.$i))
                    $paragraphs[$i]['image'] = $input['paragraph_image' . $i];
                else
                    $paragraphs[$i]['image'] = null;
                $paragraphs[$i]['active'] = $input['paragraph_active' . $i];
                $paragraphs[$i]['en'] = $input['paragraph_en' . $i];
                foreach ($system_languages as $system_language)
                    $paragraphs[$i][$system_language] = $input['paragraph_'. $system_language . $i];

                unset($input['paragraph_title_en' . $i]);
                foreach ($system_languages as $system_language)
                    unset($input['paragraph_title_' . $system_language . $i]);
                unset($input['paragraph_description_en' . $i]);
                foreach ($system_languages as $system_language)
                    unset($input['paragraph_description_' . $system_language . $i]);
                unset($input['paragraph_image' . $i]);
                unset($input['paragraph_active' . $i]);
                unset($input['paragraph_en' . $i]);
                foreach ($system_languages as $system_language)
                    unset($input['paragraph_' . $system_language . $i]);

            }
        }

        unset($input['counter']);
        unset($input['system_language']);

		// Prepare the submitted data
		$data = $this->data->prepare($input);

		// Validate the submitted data
		$messages = $this->validForCreation($data);

		// Check if the validation returned any errors
		if ($messages->isEmpty())
		{
            if (request()->hasFile('image'))
            {
                $destinationPath =  'storage/news/images';
                $file_info = getimagesize(request()->file('image'));
                if (empty($file_info)) // No Image?
                {
                    $messages->add('image', 'Please, Provide a valid image');
                }
                $file = request()->file('image');
                $attach = $destinationPath . '/' .rand().'-news-images-' . date("d-m-y-H-M")  . '-' .$file->getClientOriginalName();
                $file->move($destinationPath, ($attach));
                $data['image'] = trim($attach);
            }
            else
                $messages->add('image', 'Please, Provide the the news image');

            if (request()->hasFile('attachment'))
            {
                $destinationPath =  'storage/news/attachments';
                $file = request()->file('attachment');
                $attach = $destinationPath . '/' .rand().'-news-attachments-' . date("d-m-y-H-M")  . '-' .$file->getClientOriginalName();
                $file->move($destinationPath, ($attach));
                $data['attachment'] = trim($attach);
            }

            foreach($paragraphs as $key => $paragraph)
            {
                if ($paragraph['image'] != null)
                {
                    $destinationPath =  'storage/news/paragraphs/images';
                    $file_info = getimagesize($paragraph['image']);
                    if (empty($file_info)) // No Image?
                    {
                        $messages->add('paragraph_image'.$key, 'Please, Provide a valid image');
                    }
                    $file = $paragraph['image'];
                    $attach = $destinationPath . '/' . rand() . '-news-paragraphs-images-' . date("d-m-y-H-M") . '-' . $file->getClientOriginalName();
                    $file->move($destinationPath, ($attach));
                    $paragraph['image'] = trim($attach);
                }
            }

            if ($messages->isEmpty())
            {
                // Save the news
                $news->fill($data)->save();
                $news->created_by = Sentinel::getUser()->id;
                $news->save();

                foreach($paragraphs as $paragraph)
                {
                    $paragraph['news_id'] = $news->id;
                    $paragraph['created_by'] = Sentinel::getUser()->id;
                    NewsParagraphs::create($paragraph);
                }
            }

			// Fire the 'butterfly-effect.news.news.created' event
			$this->fireEvent('butterfly-effect.news.news.created', [ $news ]);
		}

		return [ $messages, $news ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function update($id, array $input)
	{
		// Get the news object
		$news = $this->find($id);

		// Fire the 'butterfly-effect.news.news.updating' event
		if ($this->fireEvent('butterfly-effect.news.news.updating', [ $news, $input ]) === false)
		{
			return false;
		}

        unset($input['counter']);
        unset($input['system_language']);

		// Prepare the submitted data
		$data = $this->data->prepare($input);

		// Validate the submitted data
		$messages = $this->validForUpdate($news, $data);

		// Check if the validation returned any errors
        if ($messages->isEmpty())
        {
            if (request()->hasFile('image'))
            {
                $destinationPath =  'storage/news/images';
                $file_info = getimagesize(request()->file('image'));
                if (empty($file_info)) // No Image?
                {
                    $messages->add('image', 'Please, Provide a valid image');
                }
                $file = request()->file('image');
                $attach = $destinationPath . '/' .rand().'-news-images-' . date("d-m-y-H-M")  . '-' .$file->getClientOriginalName();
                $file->move($destinationPath, ($attach));
                $data['image'] = trim($attach);
            }

            if (request()->hasFile('attachment'))
            {
                $destinationPath =  'storage/news/attachments';
                $file = request()->file('attachment');
                $attach = $destinationPath . '/' .rand().'-news-attachments-' . date("d-m-y-H-M")  . '-' .$file->getClientOriginalName();
                $file->move($destinationPath, ($attach));
                $data['attachment'] = trim($attach);
            }

            if ($messages->isEmpty())
            {
                // Save the news
                $news->fill($data)->save();
                $news->updated_by = Sentinel::getUser()->id;
                $news->save();
            }

            // Fire the 'butterfly-effect.news.news.created' event
            $this->fireEvent('butterfly-effect.news.news.created', [ $news ]);
        }

		return [ $messages, $news ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function delete($id)
	{
		// Check if the news exists
		if ($news = $this->find($id))
		{
			// Fire the 'butterfly-effect.news.news.deleting' event
			$this->fireEvent('butterfly-effect.news.news.deleting', [ $news ]);

            foreach($news->paragraphs as $paragraph)
                $paragraph->delete();

			// Delete the news entry
			$news->delete();

			// Fire the 'butterfly-effect.news.news.deleted' event
			$this->fireEvent('butterfly-effect.news.news.deleted', [ $news ]);

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
