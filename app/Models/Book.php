<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Book
 * 
 * @property string $id
 * @property string $title
 * @property string $subtitle
 * @property string|null $synopsis
 * @property int $author_id
 * @property int|null $category_id
 * @property int|null $series_id
 * @property float|null $price
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Author $author
 * @property Category|null $category
 * @property Series|null $series
 *
 * @package App\Models
 */
class Book extends Model
{
	protected $table = 'books';
	public $incrementing = false;

	protected $casts = [
		'author_id' => 'int',
		'category_id' => 'int',
		'series_id' => 'int',
		'price' => 'float'
	];

	protected $fillable = [
		'title',
		'subtitle',
		'synopsis',
		'author_id',
		'category_id',
		'series_id',
		'price'
	];

	public function author()
	{
		return $this->belongsTo(Author::class);
	}

	public function category()
	{
		return $this->belongsTo(Category::class);
	}

	public function series()
	{
		return $this->belongsTo(Series::class);
	}
}
