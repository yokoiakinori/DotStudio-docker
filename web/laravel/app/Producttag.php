<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Producttag extends Model
{
	public function product()
	{
		$this->belongsTo('App\Product');
	}
}
