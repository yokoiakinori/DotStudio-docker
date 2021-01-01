<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class FollowApiTest extends TestCase
{
	use RefreshDatabase;

	public function setUp(): void
	{
		parent::setUp();

		factory(User::class, 2)->create();
		$this->user = User::first();
	}

	/**
	 * @test
	 */
	public function shouldFollow()
	{
		$followee = 2;
		$response = $this->actingAs($this->user)
			->json('POST', route('user.follow', [
				'id' => $followee,
			]),);
		$response->assertStatus(200)
			->assertJsonFragment(["followee_id" => "2"]);
	}
}
