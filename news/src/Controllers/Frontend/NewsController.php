<?php namespace ButterflyEffect\News\Controllers\Frontend;

use ButterflyEffect\News\Models\News;
use ButterflyEffect\News\Models\Newscategory;
use Platform\Foundation\Controllers\Controller;

class NewsController extends Controller {

    /**
     * Route action get specific news-category news
     *
     * @param int $category_id selected news-category id
     * @param string $category_title selected news-category title
     * @author Amk El-Kabbany at 7 Aug 2016
     * @return View
     */
    public function getCategoryNews($category_id, $category_title)
    {
        $category = Newscategory::find($category_id);
        $category->views = $category->views++;
        $category->save();
        $news = $category->active_news();
        return view('butterfly-effect/news::news', compact('news', 'category_id', 'category_title'));
    }

    /**
     * Route action get specific news-item
     *
     * @param int $category_id selected news-category id
     * @param string $category_title selected news-category title
     * @param int $news_id selected news id
     * @param string $news_title selected news title
     * @author Amk El-Kabbany at 7 Aug 2016
     * @return View
     */
    public function getCategoryNewsItem($category_id, $category_title, $news_id, $news_title)
    {
        $news = News::find($news_id);
        $news->views = $news->views++;
        $news->save();
        $related_news = Newscategory::find($category_id)->active_news();
        return view('butterfly-effect/news::news-item', compact('news', 'related_news', 'category_id', 'category_title'));
    }

}
