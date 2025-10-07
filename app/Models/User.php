<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;


/**
 * Class User
 * 
 * @property string $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Models
 */


class User extends Authenticatable implements JWTSubject
{
	protected $table = 'users';
	public $incrementing = false;

	protected $hidden = [
		'password'
	];

	protected $fillable = [
		'id',
		'name',
		'email',
		'password'
	];

	public function getJWTIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Return an array with custom claims to be added to the JWT token.
	 */
	public function getJWTCustomClaims()
	{
		return [];
	}
}
