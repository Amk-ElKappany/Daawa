<?php namespace ButterflyEffect\News\Models;

use Cartalyst\Attributes\EntityInterface;
use Illuminate\Database\Eloquent\Model;
use Platform\Attributes\Traits\EntityTrait;
use Cartalyst\Support\Traits\NamespacedEntityTrait;

class Newscategory extends Model implements EntityInterface {

	use EntityTrait, NamespacedEntityTrait;

	/**
	 * {@inheritDoc}
	 */
	protected $table = 'newscategories';

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
	protected static $entityNamespace = 'butterfly-effect/news.newscategory';
    
    public function news()
    {
        return $this->hasMany('ButterflyEffect\News\Models\News');
    }

    public function active_news()
    {
        return News::where('newscategory_id', '=', $this->id)->where('active', '=', true)->orderBy(\DB::raw("str_to_date( date, '%m/%d/%Y' )"),'desc')->paginate(8);
    }

}
