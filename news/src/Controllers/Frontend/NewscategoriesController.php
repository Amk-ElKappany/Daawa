<?php namespace ButterflyEffect\News\Controllers\Frontend;

use ButterflyEffect\News\Models\Newscategory;
use Platform\Foundation\Controllers\Controller;

class NewscategoriesController extends Controller {

    /**
     * Route action get all active news categories
     *
     * @author Amk El-Kabbany at 7 Aug 2016
     * @return View
     */
    public function getCategories()
    {
        $categories = Newscategory::where('active', '=', true)->paginate(8);
        return view('butterfly-effect/news::categories', compact('categories'));
    }

}
