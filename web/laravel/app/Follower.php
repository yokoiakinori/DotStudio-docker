<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\User;

class Follower extends Model
{
	protected $visible = [
		'followee_id', 'user_id'
	];

	public function user()
	{
		return $this->belongsTo('App\User');
	}
}
