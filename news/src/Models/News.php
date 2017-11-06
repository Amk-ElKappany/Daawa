<?php namespace ButterflyEffect\News\Models;

use Cartalyst\Attributes\EntityInterface;
use Illuminate\Database\Eloquent\Model;
use Platform\Attributes\Traits\EntityTrait;
use Cartalyst\Support\Traits\NamespacedEntityTrait;

class News extends Model implements EntityInterface {

	use EntityTrait, NamespacedEntityTrait;

	/**
	 * {@inheritDoc}
	 */
	protected $table = 'news';

	/**
	 * {@inheritDoc}
	 */
	protected $guarded = [
		'id',
	];

	/**
	 * {@inheritDoc}
	 */
	protected $with = [
		'values.attribute',
	];

	/**
	 * {@inheritDoc}
	 */
	protected static $entityNamespace = 'butterfly-effect/news.news';

    function paragraphs()
    {
        return $this->hasMany('ButterflyEffect\News\Models\NewsParagraphs');
    }

    function active_paragraphs()
    {
        return NewsParagraphs::where('news_id', '=', $this->id)->where('active', '=', true)->get();
    }

    function category()
    {
        return $this->belongsTo('ButterflyEffect\News\Models\Newscategory', 'newscategory_id');
    }
}
