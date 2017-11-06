<?php

$array = [

	'general' => [

		'id' => 'Id',
		'title_en' => 'News Category Title',
		'description_en' => 'Brief',
		'image' => 'Image',
		'views' => 'Views',
		'active' => 'Is It Active?',
		'home' => 'Display It On Home?',
		'created_by' => 'Created By',
		'updated_by' => 'Updated By',
		'created_at' => 'Created At',
		'title_en_help' => 'Enter the News Category Title here',
		'description_en_help' => 'Enter the News Category Brief here',
		'image_help' => 'Choose the Image here',
		'views_help' => 'Number of Views here',
		'active_help' => 'Choose wither it Active or not here',
        'home_help' => 'Choose wither it displayed on the Home or not here',
	],

];

$system_languages = \ButterflyEffect\Language\Models\Language::where('id', '!=', 0)->get()->lists('prefix');
unset($system_languages[0]);
$query = \Illuminate\Support\Facades\DB::select('SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = "'.env('DB_DATABASE').'" and TABLE_NAME = "newscategories" and COLUMN_NAME like "%_en"');
foreach ($system_languages as $system_language)
{
    foreach ($query as $column)
    {
        $naming =  \Platform\Foundation\Controllers\Controller::cut_string_using_last('_', $column->COLUMN_NAME, 'left', false);
        $array['general'][$naming.'_'.$system_language] = $array['general'][$column->COLUMN_NAME].' in '.strtoupper($system_language).' Language';
    }
}

return $array;