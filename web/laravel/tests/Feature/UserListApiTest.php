<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class UserListApiTest extends TestCase
{
	use RefreshDatabase;

	/**
	 * @test
	 */
	public function allUserList()
	{
		factory(User::class, 5)->create();
		$response = $this->json('GET', route('user.list'));

		$users = User::orderBy('created_at', 'desc')->get();

		$expected_data = $users->map(function ($user) {
			return [
				'name' => $user->name,
				'id' => $user->id,
				'introduction' => 'よろしくお願いします。',
				'products' => [],
				'userthumbnail' => null
			];
		})
			->all();

		$response->assertStatus(200)
			->assertJsonCount(5, 'data')
			->assertJsonFragment([
				"data" => $expected_data,
			]);
	}
}
