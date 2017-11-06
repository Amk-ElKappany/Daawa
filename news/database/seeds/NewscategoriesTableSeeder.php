<?php namespace ButterflyEffect\News\Database\Seeds;

use ButterflyEffect\Language\Models\Language;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Platform\Foundation\Controllers\Controller;

class NewscategoriesTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
        /**
         * Duplicating each data base column ends with '_en' with the rest of current system languages
         */
        $query = DB::select('SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = "'.env('DB_DATABASE').'" and TABLE_NAME = "newscategories" and COLUMN_NAME like "%_en"');
        $languages = Language::where('id', '!=', 0)->get()->lists('prefix');
        unset($languages[0]);

        foreach ($languages as $language)
        {
            $check_query = DB::select('SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = "'.env('DB_DATABASE').'" and TABLE_NAME = "newscategories" and COLUMN_NAME like "%_'.$language.'"');
            if(empty($check_query))
                foreach ($query as $column)
                {
                    $naming =  Controller::cut_string_using_last('_', $column->COLUMN_NAME, 'left', false);
                    $column_type = DB::select('SELECT DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = "'.env('DB_DATABASE').'" and TABLE_NAME = "newscategories" and COLUMN_NAME = "'.$column->COLUMN_NAME.'"');
                    $column_maximum_length = DB::select('SELECT CHARACTER_MAXIMUM_LENGTH FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = "'.env('DB_DATABASE').'" and TABLE_NAME = "newscategories" and COLUMN_NAME = "'.$column->COLUMN_NAME.'"');
                    $temp_query = DB::statement('ALTER TABLE newscategories ADD '.$naming.'_'.$language.' '.$column_type[0]->DATA_TYPE.'('.$column_maximum_length[0]->CHARACTER_MAXIMUM_LENGTH.')'.' after '.$column->COLUMN_NAME);
                }
            $check_query = DB::select('SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = "'.env('DB_DATABASE').'" and TABLE_NAME = "newscategories" and COLUMN_NAME like "'.$language.'"');
            if(empty($check_query))
                $temp_query = DB::statement('ALTER TABLE newscategories ADD '.$language.' TinyInt(1) after en');
        }
	}

}
