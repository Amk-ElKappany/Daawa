<?php namespace ButterflyEffect\News\Controllers\Admin;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;
use Platform\Access\Controllers\AdminController;
use ButterflyEffect\News\Repositories\Newscategory\NewscategoryRepositoryInterface;
use Platform\Users\Models\User;

class NewscategoriesController extends AdminController {

	/**
	 * {@inheritDoc}
	 */
	protected $csrfWhitelist = [
		'executeAction',
	];

	/**
	 * The News repository.
	 *
	 * @var \ButterflyEffect\News\Repositories\Newscategory\NewscategoryRepositoryInterface
	 */
	protected $newscategories;

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
	 * @param  \ButterflyEffect\News\Repositories\Newscategory\NewscategoryRepositoryInterface  $newscategories
	 * @return void
	 */
	public function __construct(NewscategoryRepositoryInterface $newscategories)
	{
		parent::__construct();

		$this->newscategories = $newscategories;
	}

	/**
	 * Display a listing of newscategory.
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		return view('butterfly-effect/news::newscategories.index');
	}

	/**
	 * Datasource for the newscategory Data Grid.
	 *
	 * @return \Cartalyst\DataGrid\DataGrid
	 */
	public function grid()
	{
		$data = $this->newscategories->grid();

		$columns = [
			'id',
			'title_en',
			'description_en',
			'image',
			'views',
			'active',
			'home',
			'en',
			'created_by',
			'updated_by',
			'created_at',
		];

        $counter = 10;
        foreach (Config::get('system_languages') as $system_language)
        {
            $columns[$counter++] = 'title_'.$system_language;
            $columns[$counter++] = 'description_'.$system_language;
        }

        $settings = [
			'sort'      => 'created_at',
			'direction' => 'desc',
		];

		$transformer = function($element)
		{
			$element->edit_uri = route('admin.butterfly-effect.news.newscategories.edit', $element->id);

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
	 * Show the form for creating new newscategory.
	 *
	 * @return \Illuminate\View\View
	 */
	public function create()
	{
		return $this->showForm('create');
	}

	/**
	 * Handle posting of the form for creating new newscategory.
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store()
	{
		return $this->processForm('create');
	}

	/**
	 * Show the form for updating newscategory.
	 *
	 * @param  int  $id
	 * @return mixed
	 */
	public function edit($id)
	{
		return $this->showForm('update', $id);
	}

	/**
	 * Handle posting of the form for updating newscategory.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update($id)
	{
		return $this->processForm('update', $id);
	}

	/**
	 * Remove the specified newscategory.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function delete($id)
	{
		$type = $this->newscategories->delete($id) ? 'success' : 'error';

		$this->alerts->{$type}(
			trans("butterfly-effect/news::newscategories/".Config::get('languages')[Request::ip()]['admin']."/message.{$type}.delete")
		);

		return redirect()->route('admin.butterfly-effect.news.newscategories.all');
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
				$this->newscategories->{$action}($row);
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
		// Do we have a newscategory identifier?
		if (isset($id))
		{
			if ( ! $newscategory = $this->newscategories->find($id))
			{
				$this->alerts->error(trans('butterfly-effect/news::newscategories/'.Config::get('languages')[Request::ip()]['admin'].'/message.not_found', compact('id')));

				return redirect()->route('admin.butterfly-effect.news.newscategories.all');
			}
		}
		else
		{
			$newscategory = $this->newscategories->createModel();
		}

		// Show the page
		return view('butterfly-effect/news::newscategories.form', compact('mode', 'newscategory'));
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
		// Store the newscategory
		list($messages) = $this->newscategories->store($id, request()->all());

		// Do we have any errors?
		if ($messages->isEmpty())
		{
			$this->alerts->success(trans("butterfly-effect/news::newscategories/".Config::get('languages')[Request::ip()]['admin']."/message.success.{$mode}"));

			return redirect()->route('admin.butterfly-effect.news.newscategories.all');
		}

		$this->alerts->error($messages, 'form');

		return redirect()->back()->withInput();
	}

}
