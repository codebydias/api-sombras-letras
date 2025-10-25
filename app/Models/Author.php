<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Author
 * 
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|Book[] $books
 *
 * @package App\Models
 */
class Author extends Model
{
	protected $table = 'authors';

	protected $fillable = [
		'name'
	];

	public function books()
	{
		return $this->hasMany(Book::class);
	}
}
