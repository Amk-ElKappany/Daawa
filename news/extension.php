<?php

use Illuminate\Foundation\Application;
use Cartalyst\Extensions\ExtensionInterface;
use Cartalyst\Settings\Repository as Settings;
use Cartalyst\Permissions\Container as Permissions;

return [

	/*
	|--------------------------------------------------------------------------
	| Name
	|--------------------------------------------------------------------------
	|
	| This is your extension name and it is only required for
	| presentational purposes.
	|
	*/

	'name' => 'News',

	/*
	|--------------------------------------------------------------------------
	| Slug
	|--------------------------------------------------------------------------
	|
	| This is your extension unique identifier and should not be changed as
	| it will be recognized as a new extension.
	|
	| Ideally, this should match the folder structure within the extensions
	| folder, but this is completely optional.
	|
	*/

	'slug' => 'butterfly-effect/news',

	/*
	|--------------------------------------------------------------------------
	| Author
	|--------------------------------------------------------------------------
	|
	| Because everybody deserves credit for their work, right?
	|
	*/

	'author' => 'Alaa M. El-Kabbany',

	/*
	|--------------------------------------------------------------------------
	| Description
	|--------------------------------------------------------------------------
	|
	| One or two sentences describing the extension for users to view when
	| they are installing the extension.
	|
	*/

	'description' => ' Manage news displayed on the frontend ',

	/*
	|--------------------------------------------------------------------------
	| Version
	|--------------------------------------------------------------------------
	|
	| Version should be a string that can be used with version_compare().
	| This is how the extensions versions are compared.
	|
	*/

	'version' => '0.1.0',

	/*
	|--------------------------------------------------------------------------
	| Requirements
	|--------------------------------------------------------------------------
	|
	| List here all the extensions that this extension requires to work.
	| This is used in conjunction with composer, so you should put the
	| same extension dependencies on your main composer.json require
	| key, so that they get resolved using composer, however you
	| can use without composer, at which point you'll have to
	| ensure that the required extensions are available.
	|
	*/

	'require' => [
        'platform/operations',
        'butterfly-effect/language',
    ],

	/*
	|--------------------------------------------------------------------------
	| Autoload Logic
	|--------------------------------------------------------------------------
	|
	| You can define here your extension autoloading logic, it may either
	| be 'composer', 'platform' or a 'Closure'.
	|
	| If composer is defined, your composer.json file specifies the autoloading
	| logic.
	|
	| If platform is defined, your extension receives convetion autoloading
	| based on the Platform standards.
	|
	| If a Closure is defined, it should take two parameters as defined
	| bellow:
	|
	|	object \Composer\Autoload\ClassLoader      $loader
	|	object \Illuminate\Foundation\Application  $app
	|
	| Supported: "composer", "platform", "Closure"
	|
	*/

	'autoload' => 'composer',

	/*
	|--------------------------------------------------------------------------
	| Service Providers
	|--------------------------------------------------------------------------
	|
	| Define your extension service providers here. They will be dynamically
	| registered without having to include them in app/config/app.php.
	|
	*/

	'providers' => [

		'ButterflyEffect\News\Providers\NewscategoryServiceProvider',
		'ButterflyEffect\News\Providers\NewsServiceProvider',

	],

	/*
	|--------------------------------------------------------------------------
	| Routes
	|--------------------------------------------------------------------------
	|
	| Closure that is called when the extension is started. You can register
	| any custom routing logic here.
	|
	| The closure parameters are:
	|
	|	object \Cartalyst\Extensions\ExtensionInterface  $extension
	|	object \Illuminate\Foundation\Application        $app
	|
	*/

	'routes' => function(ExtensionInterface $extension, Application $app)
	{
		Route::group([
				'prefix'    => admin_uri().'/news/news-categories',
				'namespace' => 'ButterflyEffect\News\Controllers\Admin',
			], function()
			{
				Route::get('/' , ['as' => 'admin.butterfly-effect.news.newscategories.all', 'uses' => 'NewscategoriesController@index']);
				Route::post('/', ['as' => 'admin.butterfly-effect.news.newscategories.all', 'uses' => 'NewscategoriesController@executeAction']);

				Route::get('grid', ['as' => 'admin.butterfly-effect.news.newscategories.grid', 'uses' => 'NewscategoriesController@grid']);

				Route::get('create' , ['as' => 'admin.butterfly-effect.news.newscategories.create', 'uses' => 'NewscategoriesController@create']);
				Route::post('create', ['as' => 'admin.butterfly-effect.news.newscategories.create', 'uses' => 'NewscategoriesController@store']);

				Route::get('{id}'   , ['as' => 'admin.butterfly-effect.news.newscategories.edit'  , 'uses' => 'NewscategoriesController@edit']);
				Route::post('{id}'  , ['as' => 'admin.butterfly-effect.news.newscategories.edit'  , 'uses' => 'NewscategoriesController@update']);

				Route::delete('{id}', ['as' => 'admin.butterfly-effect.news.newscategories.delete', 'uses' => 'NewscategoriesController@delete']);
			});

        Route::group([
				'prefix'    => admin_uri().'/news/news',
				'namespace' => 'ButterflyEffect\News\Controllers\Admin',
			], function()
			{
				Route::get('/' , ['as' => 'admin.butterfly-effect.news.news.all', 'uses' => 'NewsController@index']);
				Route::post('/', ['as' => 'admin.butterfly-effect.news.news.all', 'uses' => 'NewsController@executeAction']);

				Route::get('grid', ['as' => 'admin.butterfly-effect.news.news.grid', 'uses' => 'NewsController@grid']);

				Route::get('create' , ['as' => 'admin.butterfly-effect.news.news.create', 'uses' => 'NewsController@create']);
				Route::post('create', ['as' => 'admin.butterfly-effect.news.news.create', 'uses' => 'NewsController@store']);

				Route::get('{id}'   , ['as' => 'admin.butterfly-effect.news.news.edit'  , 'uses' => 'NewsController@edit']);
				Route::post('{id}'  , ['as' => 'admin.butterfly-effect.news.news.edit'  , 'uses' => 'NewsController@update']);

				Route::delete('{id}', ['as' => 'admin.butterfly-effect.news.news.delete', 'uses' => 'NewsController@delete']);

				Route::post('ajax/add/paragraph', ['as' => 'butterfly-effect.admin.ajax.news.news.add-paragraph', 'uses' => 'NewsController@addNewParagraph']);
				Route::post('ajax/edit/paragraph-title', ['as' => 'butterfly-effect.admin.ajax.news.news.edit-paragraph-title', 'uses' => 'NewsController@editParagraphTitle']);
				Route::post('ajax/edit/paragraph-description', ['as' => 'butterfly-effect.admin.ajax.news.news.edit-paragraph-description', 'uses' => 'NewsController@editParagraphDescription']);
				Route::post('ajax/edit/paragraph-active', ['as' => 'butterfly-effect.admin.ajax.news.news.edit-paragraph-active', 'uses' => 'NewsController@editParagraphActive']);
				Route::post('ajax/edit/paragraph-languages', ['as' => 'butterfly-effect.admin.ajax.news.news.edit-paragraph-languages', 'uses' => 'NewsController@editParagraphLanguage']);
				Route::post('ajax/delete/paragraph', ['as' => 'butterfly-effect.admin.ajax.news.news.delete-paragraph', 'uses' => 'NewsController@deleteParagraph']);
			});

		Route::group([
			'prefix'    => 'news',
			'namespace' => 'ButterflyEffect\News\Controllers\Frontend',
		], function()
		{
            Route::get('/categories', ['as' => 'butterfly-effect.frontend.news.categories', 'uses' => 'NewscategoriesController@getCategories']);
            Route::get('/categories/{category_id}/{category_title}/news', ['as' => 'butterfly-effect.frontend.news.news', 'uses' => 'NewsController@getCategoryNews']);
            Route::get('/categories/{category_id}/{category_title}/news/{news_id}/{news_title}', ['as' => 'butterfly-effect.frontend.news.news-item', 'uses' => 'NewsController@getCategoryNewsItem']);
		});
	},

	/*
	|--------------------------------------------------------------------------
	| Database Seeds
	|--------------------------------------------------------------------------
	|
	| Platform provides a very simple way to seed your database with test
	| data using seed classes. All seed classes should be stored on the
	| `database/seeds` directory within your extension folder.
	|
	| The order you register your seed classes on the array below
	| matters, as they will be ran in the exact same order.
	|
	| The seeds array should follow the following structure:
	|
	|	Vendor\Namespace\Database\Seeds\FooSeeder
	|	Vendor\Namespace\Database\Seeds\BarSeeder
	|
	*/

	'seeds' => [

		'ButterflyEffect\News\Database\Seeds\NewscategoriesTableSeeder',
		'ButterflyEffect\News\Database\Seeds\NewsTableSeeder',

	],

	/*
	|--------------------------------------------------------------------------
	| Permissions
	|--------------------------------------------------------------------------
	|
	| Register here all the permissions that this extension has. These will
	| be shown in the user management area to build a graphical interface
	| where permissions can be selected to allow or deny user access.
	|
	| For detailed instructions on how to register the permissions, please
	| refer to the following url https://cartalyst.com/manual/permissions
	|
	*/

	'permissions' => function(Permissions $permissions)
	{
		$permissions->group('newscategory', function($g)
		{
			$g->name = 'Newscategories';

			$g->permission('newscategory.index', function($p)
			{
				$p->label = trans('butterfly-effect/news::newscategories/permissions.index');

				$p->controller('ButterflyEffect\News\Controllers\Admin\NewscategoriesController', 'index, grid');
			});

			$g->permission('newscategory.create', function($p)
			{
				$p->label = trans('butterfly-effect/news::newscategories/permissions.create');

				$p->controller('ButterflyEffect\News\Controllers\Admin\NewscategoriesController', 'create, store');
			});

			$g->permission('newscategory.edit', function($p)
			{
				$p->label = trans('butterfly-effect/news::newscategories/permissions.edit');

				$p->controller('ButterflyEffect\News\Controllers\Admin\NewscategoriesController', 'edit, update');
			});

			$g->permission('newscategory.delete', function($p)
			{
				$p->label = trans('butterfly-effect/news::newscategories/permissions.delete');

				$p->controller('ButterflyEffect\News\Controllers\Admin\NewscategoriesController', 'delete');
			});
		});

		$permissions->group('news', function($g)
		{
			$g->name = 'News';

			$g->permission('news.index', function($p)
			{
				$p->label = trans('butterfly-effect/news::news/permissions.index');

				$p->controller('ButterflyEffect\News\Controllers\Admin\NewsController', 'index, grid');
			});

			$g->permission('news.create', function($p)
			{
				$p->label = trans('butterfly-effect/news::news/permissions.create');

				$p->controller('ButterflyEffect\News\Controllers\Admin\NewsController', 'create, store');
			});

			$g->permission('news.edit', function($p)
			{
				$p->label = trans('butterfly-effect/news::news/permissions.edit');

				$p->controller('ButterflyEffect\News\Controllers\Admin\NewsController', 'edit, update');
			});

			$g->permission('news.delete', function($p)
			{
				$p->label = trans('butterfly-effect/news::news/permissions.delete');

				$p->controller('ButterflyEffect\News\Controllers\Admin\NewsController', 'delete');
			});
		});
	},

	/*
	|--------------------------------------------------------------------------
	| Widgets
	|--------------------------------------------------------------------------
	|
	| Closure that is called when the extension is started. You can register
	| all your custom widgets here. Of course, Platform will guess the
	| widget class for you, this is just for custom widgets or if you
	| do not wish to make a new class for a very small widget.
	|
	*/

	'widgets' => function()
	{

	},

	/*
	|--------------------------------------------------------------------------
	| Settings
	|--------------------------------------------------------------------------
	|
	| Register any settings for your extension. You can also configure
	| the namespace and group that a setting belongs to.
	|
	*/

	'settings' => function(Settings $settings, Application $app)
	{

	},

	/*
	|--------------------------------------------------------------------------
	| Menus
	|--------------------------------------------------------------------------
	|
	| You may specify the default various menu hierarchy for your extension.
	| You can provide a recursive array of menu children and their children.
	| These will be created upon installation, synchronized upon upgrading
	| and removed upon uninstallation.
	|
	| Menu children are automatically put at the end of the menu for extensions
	| installed through the Operations extension.
	|
	| The default order (for extensions installed initially) can be
	| found by editing app/config/platform.php.
	|
	*/

	'menus' => [

		'admin' => [
			[
				'slug' => 'admin-butterfly-effect-news',
				'name' => 'News',
				'class' => 'fa fa-newspaper-o',
				'uri' => 'news',
				'regex' => '/:admin\/news/i',
				'children' => [
					[
						'class' => 'fa fa-newspaper-o',
						'name' => 'News Categories',
						'uri' => 'news/news-categories',
						'regex' => '/:admin\/news\/newscategory/i',
						'slug' => 'admin-butterfly-effect-news-newscategory',
					],
					[
						'class' => 'fa fa-newspaper-o',
						'name' => 'News',
						'uri' => 'news/news',
						'regex' => '/:admin\/news\/news/i',
						'slug' => 'admin-butterfly-effect-news-news',
					],
				],
			],
		],
		'main' => [
			
		],
	],

];
