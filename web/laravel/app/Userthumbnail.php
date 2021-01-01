<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class Userthumbnail extends Model
{

	public function __construct(array $attributes = [])
	{
		parent::__construct($attributes);

		if (!Arr::get($this->attributes, 'filestring')) {
			$this->setFilestring();
		}
	}

	public function user()
	{
		return $this->belongsTo('App\User');
	}

	private function setFilestring()
	{
		$this->attributes['filestring'] = 'dotstudio-default-thumbnail';
	}

	public function getUrlAttribute()
	{
		return Storage::cloud()->url($this->attributes['filename']);
	}

	protected $appends = [
		'url',
	];

	protected $visible = [
		'id', 'user', 'url'
	];
}
