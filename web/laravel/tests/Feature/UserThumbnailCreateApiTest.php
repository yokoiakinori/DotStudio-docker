<?php

namespace Tests\Feature;

use App\Userthumbnail;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserThumbnailCreateApiTest extends TestCase
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
    public function shouldCreateThumbnail()
    {
        Storage::fake('s3');

        $response = $this->actingAs($this->user)
            ->json('POST', route('thumbnail.create'));
        $response->assertStatus(201);

        $userthumbnail = Userthumbnail::first();

        $this->assertMatchesRegularExpression('/dotstudio-default-thumbnail/', $userthumbnail->filestring);
    }
}
