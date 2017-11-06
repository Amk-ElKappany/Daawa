<?php

$array = [

	'general' => [

		'id' => 'Id',
		'related_news' => 'Related News',
		'newscategory_id' => 'News Category',
		'title_en' => 'News Title',
		'image' => 'Image',
		'date' => 'Date',
		'views' => 'Views',
		'attachment' => 'Attachment',
		'has_video' => 'Does it Contains a Video?',
		'has_sound' => 'Does it Contains a Sound File',
        'active' => 'Is it Active?',
		'home' => 'Display it on Home?',
		'description_en' => 'Description',
        'news_paragraphs_header' => 'Manage News Paragraphs',
        'paragraph_title_en' => 'Paragraph Title',
        'paragraph_description_en' => 'Paragraph Description',
        'paragraph_active' => 'Is it Active?',
        'paragraph_image' => 'Paragraph Image',
        'video_link' => 'Video Link (YouTube)',
		'video_title_en' => 'Video Title',
		'video_description_en' => 'Video Description',
		'sound_link' => 'Sound File Link (SoundCloud)',
		'sound_title_en' => 'Sound File Title',
		'sound_description_en' => 'Sound File Description',
		'video_header' => 'Video (YouTube) Main Information',
		'sound_header' => 'Sound (SoundCloud) Main Information',
		'created_by' => 'Created By',
		'updated_by' => 'Updated By',
		'created_at' => 'Created At',
		'newscatgory_id_help' => 'Choose to which News Category it belong here',
		'title_en_help' => 'Enter the News Title here',
		'image_help' => 'Choose the Image here',
		'date_help' => 'Enter the Date here',
		'views_help' => 'Number of Views here',
		'attachment_help' => 'Choose the Attachment here',
		'has_video_help' => 'Choose wither it contains a Video or not here',
		'has_sound_help' => 'Choose wither it contains a Sound File or not here',
		'active_help' => 'Choose wither it Active or not here',
		'home_help' => 'Choose wither it displayed on the Home or not here',
		'description_en_help' => 'Enter the Description here',
		'video_link_help' => 'Enter the Video link here',
		'video_title_en_help' => 'Enter the Video Title here',
		'video_description_en_help' => 'Enter the Video Description here',
		'sound_link_help' => 'Enter the Sound File link here',
		'sound_title_en_help' => 'Enter the Sound File Title here',
		'sound_description_en_help' => 'Enter the Sound File Description here',
        'paragraph_title_en_help' => 'Enter the Paragraph Title here',
        'paragraph_description_en_help' => 'Enter the Paragraph Description here',
        'paragraph_active_help' => 'Choose wither it Active or not here',
        'paragraph_image_help' => 'Choose the Paragraph Image here',
    ],

];

$system_languages = \ButterflyEffect\Language\Models\Language::where('id', '!=', 0)->get()->lists('prefix');
unset($system_languages[0]);
$query = \Illuminate\Support\Facades\DB::select('SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = "'.env('DB_DATABASE').'" and TABLE_NAME = "news" and COLUMN_NAME like "%_en"');
foreach ($system_languages as $system_language)
{
    foreach ($query as $column)
    {
        $naming =  \Platform\Foundation\Controllers\Controller::cut_string_using_last('_', $column->COLUMN_NAME, 'left', false);
        $array['general'][$naming.'_'.$system_language] = $array['general'][$column->COLUMN_NAME].' in '.strtoupper($system_language).' Language';
        $array['general'][$naming.'_'.$system_language.'_help'] = $array['general'][$column->COLUMN_NAME.'_help'].' in '.strtoupper($system_language).' Language';
    }
}

$query = \Illuminate\Support\Facades\DB::select('SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = "'.env('DB_DATABASE').'" and TABLE_NAME = "news_paragraphs" and COLUMN_NAME like "%_en"');
foreach ($system_languages as $system_language)
{
    foreach ($query as $column)
    {
        $naming =  \Platform\Foundation\Controllers\Controller::cut_string_using_last('_', $column->COLUMN_NAME, 'left', false);
        $array['general']['paragraph_'.$naming.'_'.$system_language] = $array['general']['paragraph_'.$column->COLUMN_NAME].' in '.strtoupper($system_language).' Language';
    }
}
return $array;