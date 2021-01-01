<?php

namespace Tests\Feature;

use App\User;
use App\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class CurrentUserApiTest extends TestCase
{
	use RefreshDatabase;

	/**
	 * @test
	 */
	public function CurrentUserBack()
	{
		factory(User::class, 5)->create();
		$userid = mt_rand(1, 5);
		$response = $this->json('GET', route('user.current', [
			'id' => $userid,
		]));

		$response->assertStatus(200)
			->assertJson([['id' => $userid]]);
	}
}
