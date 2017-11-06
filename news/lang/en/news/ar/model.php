<?php

$array = [

	'general' => [

        'id' => 'عدد تسلسلى',
		'related_news' => 'أخبار متعلقة',
		'newscategory_id' => 'تابع لفئة أخبار',
		'title_en' => 'العنوان',
		'image' => 'الصورة',
		'date' => 'التاريخ',
		'views' => 'عدد المشاهدات',
		'attachment' => 'المرفقات',
		'has_video' => 'يحتوى على فيديو؟',
		'has_sound' => 'يحتوى على ملف صوتى؟',
        'active' => 'هل هو نشطا؟ً',
		'home' => 'هل يتم عرضه بالصفحة ألرئيسية؟',
		'description_en' => 'الوصف',
        'news_paragraphs_header' => 'ألتحكم فى ألقطع ألنصية للأخبار',
        'paragraph_title_en' => 'عنوان القطعة النصية',
        'paragraph_description_en' => 'وصف القطعة النصية',
        'paragraph_image' => 'صورة القطعة النصية',
        'paragraph_active_help' => 'هل هو نشطا؟',
        'video_link' => 'رابط الفيديو (YouTube)',
		'video_title_en' => 'عنوان الفيديو',
		'video_description_en' => 'وصف الفيديو',
		'sound_link' => 'رابط الملف الصوتى (SoundCloud)',
		'sound_title_en' => 'عنوان الملف الصوتى',
		'sound_description_en' => 'وصف الملف الصوتى',
        'video_header' => 'ألمعلومات الرئيسية للفيديو (YouTube)',
        'sound_header' => 'ألمعلومات الرئيسية للملف الصوتى (SoundCloud)',
        'created_by' => 'تم الإنشاء بواسطة',
        'updated_by' => 'تم التعديل بواسطة',
        'created_at' => 'تم الإنشاء فى تمام',
        'title_en_help' => 'من فضلك إدخل ألعنوان هنا',
        'image_help' => 'من فضلك إختر الصورة هنا',
        'views_help' => 'عدد المشاهدات',
        'description_en_help' => 'من فضلك إدخل ألوصف هنا',
        'active_help' => 'من فضلك إختر إذا كان نشطاً ام لا من هنا',
		'newscatgory_id_help' => 'من فضلك إختر تابع لأى فئة أخبار من هنا',
		'date_help' => 'من فضلك إدخل التاريخ هنا',
		'attachment_help' => 'من فضلك إختر المرفقات هنا',
		'has_video_help' => 'من فضلك إختر إذا كان يحتوى على فيديو ام لا من هنا',
		'has_sound_help' => 'من فضلك إختر إذا كان يحتوى على ملف صوتى ام لا من هنا',
		'home_help' => 'من فضلك إختر إذا كان يتم عرضه بالصفحة ألرئيسية ام لا من هنا',
		'video_title_en_help' => 'من فضلك إدخل عنوان الفيديو هنا',
		'video_link_help' => 'من فضلك إدخل رابط الفيديو هنا',
		'video_description_en_help' => 'من فضلك إدخل وصف الفيديو هنا',
		'sound_title_en_help' => 'من فضلك إدخل عنوان الملف الصوتى هنا',
		'sound_link_help' => 'من فضلك إدخل رابط الملف الصوتى هنا',
		'sound_description_en_help' => 'من فضلك إدخل وصف الملف الصوتى هنا',
        'paragraph_title_en_help' => 'من فضلك إدخل عنوان القطعة النصية هنا',
        'paragraph_description_en_help' => 'من فضلك إدخل وصف القطعة النصية هنا',
        'paragraph_image_help' => 'من فضلك إدخل صورة القطعة النصية هنا',
        'paragraph_active_help_help' => 'من فضلك إختر إذا كان نشطاً ام لا من هنا',
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
        $array['general'][$naming.'_'.$system_language] = $system_language.$array['general'][$column->COLUMN_NAME].' بلغة ال ';
    }
}
$query = \Illuminate\Support\Facades\DB::select('SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = "'.env('DB_DATABASE').'" and TABLE_NAME = "news_paragraphs" and COLUMN_NAME like "%_en"');
foreach ($system_languages as $system_language)
{
    foreach ($query as $column)
    {
        $naming =  \Platform\Foundation\Controllers\Controller::cut_string_using_last('_', $column->COLUMN_NAME, 'left', false);
        $array['general']['paragraph_'.$naming.'_'.$system_language] = $system_language.$array['general']['paragraph_'.$column->COLUMN_NAME].' بلغة ال ';
    }
}
return $array;
