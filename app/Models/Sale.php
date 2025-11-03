<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Sale
 * 
 * @property string $id
 * @property int|null $serie_id
 * @property string|null $book_id
 * @property float|null $sale_price
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $starts_at
 * @property Carbon|null $ends_at
 * 
 * @property Book|null $book
 * @property Series|null $series
 *
 * @package App\Models
 */
class Sale extends Model
{
	protected $table = 'sales';
	public $incrementing = false;

	protected $casts = [
		'serie_id' => 'int',
		'sale_price' => 'float',
		'starts_at' => 'datetime',
		'ends_at' => 'datetime'
	];

	protected $fillable = [
		'serie_id',
		'book_id',
		'sale_price',
		'starts_at',
		'ends_at'
	];

	public function book()
	{
		return $this->belongsTo(Book::class);
	}

	public function series()
	{
		return $this->belongsTo(Series::class, 'serie_id');
	}
}
