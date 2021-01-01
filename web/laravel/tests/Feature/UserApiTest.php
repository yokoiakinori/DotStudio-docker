<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;


class UserApiTest extends TestCase
{
	use RefreshDatabase;

	public function setUp(): void
	{
		parent::setUp();

		$this->user = factory(User::class)->create();
	}

	/**
	 * @test
	 */
	public function should_loggedUserback()
	{
		$response = $this->actingAs($this->user)->json('GET', route('user'));

		$response
			->assertStatus(200)
			->assertJson([
				'name' => $this->user->name,
			]);
	}
}
