<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Series
 * 
 * @property int $id
 * @property string $name
 * @property string|null $resume
 * @property float|null $price
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|Book[] $books
 *
 * @package App\Models
 */
class Series extends Model
{
	protected $table = 'series';

	protected $casts = [
		'price' => 'float'
	];

	protected $fillable = [
		'name',
		'resume',
		'price'
	];

	public function books()
	{
		return $this->hasMany(Book::class);
	}
}
