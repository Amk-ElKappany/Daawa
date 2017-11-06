<?php

$array = [

	'general' => [

        'id' => 'عدد تسلسلى',
		'title_en' => 'عنوان فئة الأخبار',
		'image' => 'الصورة',
		'views' => 'عدد المشاهدات',
        'active' => 'هل هو نشطا؟ً',
		'home' => 'هل يتم عرضه بالصفحة ألرئيسية؟',
		'description_en' => 'الوصف',
        'created_by' => 'تم الإنشاء بواسطة',
        'updated_by' => 'تم التعديل بواسطة',
        'created_at' => 'تم الإنشاء فى تمام',
        'title_en_help' => 'من فضلك إدخل ألعنوان هنا',
        'image_help' => 'من فضلك إختر الصورة هنا',
        'views_help' => 'عدد المشاهدات',
        'description_en_help' => 'من فضلك إدخل ألوصف هنا',
        'active_help' => 'من فضلك إختر إذا كان نشطاً ام لا من هنا',
        'home_help' => 'من فضلك إختر إذا كان يتم عرضه بالصفحة ألرئيسية ام لا من هنا',
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
        $array['general'][$naming.'_'.$system_language] = $system_language.$array['general'][$column->COLUMN_NAME].' بلغة ال ';
    }
}
return $array;
