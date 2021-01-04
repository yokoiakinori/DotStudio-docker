<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(App\Userthumbnail::class, function (Faker $faker) {
	return [
		'user_id' => fn () => factory(App\User::class)->create()->id,
		'filestring' => 'dotstudio-default-thumbnail',
		'filename' => 'dotstudio-default-thumbnail.png'
	];
});
