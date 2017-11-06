<?php namespace ButterflyEffect\News\Controllers\Admin;

use ButterflyEffect\News\Models\Newscategory;
use ButterflyEffect\News\Models\NewsParagraphs;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;
use Platform\Access\Controllers\AdminController;
use ButterflyEffect\News\Repositories\News\NewsRepositoryInterface;
use Platform\Users\Models\User;

class NewsController extends AdminController {

	/**
	 * {@inheritDoc}
	 */
	protected $csrfWhitelist = [
		'executeAction',
	];

	/**
	 * The News repository.
	 *
	 * @var \ButterflyEffect\News\Repositories\News\NewsRepositoryInterface
	 */
	protected $news;

	/**
	 * Holds all the mass actions we can execute.
	 *
	 * @var array
	 */
	protected $actions = [
		'delete',
		'enable',
		'disable',
	];

	/**
	 * Constructor.
	 *
	 * @param  \ButterflyEffect\News\Repositories\News\NewsRepositoryInterface  $news
	 * @return void
	 */
	public function __construct(NewsRepositoryInterface $news)
	{
		parent::__construct();

		$this->news = $news;
	}

	/**
	 * Display a listing of news.
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		return view('butterfly-effect/news::news.index');
	}

	/**
	 * Datasource for the news Data Grid.
	 *
	 * @return \Cartalyst\DataGrid\DataGrid
	 */
	public function grid()
	{
		$data = $this->news->grid();

		$columns = [
			'id',
			'newscategory_id',
			'title_en',
			'image',
			'date',
			'views',
			'attachment',
			'has_video',
			'has_sound',
			'active',
			'home',
			'description_en',
			'video_link',
			'video_title_en',
			'video_description_en',
			'sound_link',
			'sound_title_en',
			'sound_description_en',
			'en',
			'created_by',
			'updated_by',
			'created_at',
		];

        $counter = 22;
        foreach (Config::get('system_languages') as $system_language)
        {
            $columns[$counter++] = 'title_'.$system_language;
            $columns[$counter++] = 'description_'.$system_language;
            $columns[$counter++] = 'video_title_'.$system_language;
            $columns[$counter++] = 'video_description_'.$system_language;
            $columns[$counter++] = 'sound_title_'.$system_language;
            $columns[$counter++] = 'sound_description_'.$system_language;
        }

		$settings = [
			'sort'      => 'created_at',
			'direction' => 'desc',
		];

		$transformer = function($element)
		{
			$element->edit_uri = route('admin.butterfly-effect.news.news.edit', $element->id);

            $input_name = 'title_'.Config::get('languages')[Request::ip()]['admin'];
            $element->newscategory_id = Newscategory::find($element->newscategory_id)->$input_name;

            $title = [];
            foreach (Config::get('system_languages') as $i => $system_language)
            {
                $input_name = 'title_'.$system_language;
                $title[$i] = $element->$input_name;
            }
            $element->title_languages = $title;

            $user = User::find($element->created_by);
            $element->created_by = $user->first_name.' '.$user->last_name;
            if($element->updated_by != null)
            {
                $user = User::find($element->updated_by);
                $element->updated_by = $user->first_name . ' ' . $user->last_name;
            }
            else
                $element->updated_by = '';

			return $element;
		};

		return datagrid($data, $columns, $settings, $transformer);
	}

	/**
	 * Show the form for creating new news.
	 *
	 * @return \Illuminate\View\View
	 */
	public function create()
	{
		return $this->showForm('create');
	}

	/**
	 * Handle posting of the form for creating new news.
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store()
	{
		return $this->processForm('create');
	}

	/**
	 * Show the form for updating news.
	 *
	 * @param  int  $id
	 * @return mixed
	 */
	public function edit($id)
	{
		return $this->showForm('update', $id);
	}

	/**
	 * Handle posting of the form for updating news.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update($id)
	{
		return $this->processForm('update', $id);
	}

	/**
	 * Remove the specified news.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function delete($id)
	{
		$type = $this->news->delete($id) ? 'success' : 'error';

		$this->alerts->{$type}(
			trans("butterfly-effect/news::news/".Config::get('languages')[Request::ip()]['admin']."/message.{$type}.delete")
		);

		return redirect()->route('admin.butterfly-effect.news.news.all');
	}

	/**
	 * Executes the mass action.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function executeAction()
	{
		$action = request()->input('action');

		if (in_array($action, $this->actions))
		{
			foreach (request()->input('rows', []) as $row)
			{
				$this->news->{$action}($row);
			}

			return response('Success');
		}

		return response('Failed', 500);
	}

	/**
	 * Shows the form.
	 *
	 * @param  string  $mode
	 * @param  int  $id
	 * @return mixed
	 */
	protected function showForm($mode, $id = null)
	{
		// Do we have a news identifier?
		if (isset($id))
		{
			if ( ! $news = $this->news->find($id))
			{
				$this->alerts->error(trans('butterfly-effect/news::news/'.Config::get('languages')[Request::ip()]['admin'].'/message.not_found', compact('id')));

				return redirect()->route('admin.butterfly-effect.news.news.all');
			}
            $categories = Newscategory::all();
        }
		else
		{
			$news = $this->news->createModel();
            $categories = Newscategory::where('active', '=', true)->get();
		}
        Config::set('paragraph_counter', '');
        Config::set('system_languages', ['']);

		// Show the page
		return view('butterfly-effect/news::news.form', compact('mode', 'news', 'categories'));
	}

	/**
	 * Processes the form.
	 *
	 * @param  string  $mode
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	protected function processForm($mode, $id = null)
	{
		// Store the news
		list($messages) = $this->news->store($id, request()->all());

		// Do we have any errors?
		if ($messages->isEmpty())
		{
			$this->alerts->success(trans("butterfly-effect/news::news/".Config::get('languages')[Request::ip()]['admin']."/message.success.{$mode}"));

			return redirect()->route('admin.butterfly-effect.news.news.all');
		}

		$this->alerts->error($messages, 'form');

        Config::set('paragraph_counter', request()->input('counter'));
        Config::set('system_languages', request()->input('system_languages'));

		return redirect()->back()->withInput();
	}

    /**
     * <Ajax POST Action -.addParagraph script.blade.php->
     * Route action add new paragraph to selected news
     *
     * @author Amk El-Kabbany at 8 April 2017
     * @return array
     */
    public function addNewParagraph()
    {
        $id =  $_POST['id'];
        $paragraphs = array(
            'news_id' => $id,
            'created_by' => Sentinel::getUser()->id
        );
        $object = NewsParagraphs::create($paragraphs);
        $user = Sentinel::getUser();
        $object->save();

        return [$object->id, $user->first_name.' '.$user->last_name] ;
    }

    /**
     * <Ajax POST Action -.deleteParagraph script.blade.php->
     * Route action delete selected news paragraph
     *
     * @author Amk El-Kabbany at 8 April 2017
     * @return boolean
     */
    public function deleteParagraph()
    {
        $id =  $_POST['id'];
        NewsParagraphs::find($id)->delete();
        exit(json_encode(true)) ;
    }

    /**
     * <Ajax POST Action -.title gallery.blade.php->
     * Route action edit selected news title
     *
     * @author Amk El-Kabbany at 8 April 2017
     * @return array
     */
    public function editParagraphTitle()
    {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $object = NewsParagraphs::find($id);
        $object->$name = $_POST['value'];
        $user = Sentinel::getUser();
        $object->updated_by = $user->id;
        $object->save();

        exit(json_encode([$name.$id, $user->first_name.' '.$user->last_name, $id])) ;
    }

    /**
     * <Ajax POST Action -.description script.blade.php->
     * Route action edit selected news paragraph description
     *
     * @author Amk El-Kabbany at 7 Aug 2016
     * @return array
     */
    public function editParagraphDescription()
    {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $object = NewsParagraphs::find($id);
        $object->$name = $_POST['value'];
        $user = Sentinel::getUser();
        $object->updated_by = $user->id;
        $object->save();

        exit(json_encode([$name.$id, $user->first_name.' '.$user->last_name, $id])) ;
    }

    /**
     * <Ajax POST Action -.active script.blade.php->
     * Route action edit selected news paragraph active attribute
     *
     * @author Amk El-Kabbany at 7 Aug 2016
     * @return array
     */
    public function editParagraphActive()
    {
        $id = $_POST['id'];
        $object = NewsParagraphs::find($id);
        $object->active = ($_POST['value'] == 'true')? true : false;
        $user = Sentinel::getUser();
        $object->updated_by = $user->id;
        $object->save();

        exit(json_encode(['active'.$id, $user->first_name.' '.$user->last_name, $id])) ;
    }

    /**
     * <Ajax POST Action -.language script.blade.php->
     * Route action edit selected news paragraph languages display option
     *
     * @author Amk El-Kabbany at 7 Aug 2016
     * @return array
     */
    public function editParagraphLanguage()
    {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $object = NewsParagraphs::find($id);
        $object->$name = ($_POST['value'] == 'true')? true : false;
        $user = Sentinel::getUser();
        $object->updated_by = $user->id;
        $object->save();

        exit(json_encode(['language'.$id, $user->first_name.' '.$user->last_name, $id])) ;
    }
}
