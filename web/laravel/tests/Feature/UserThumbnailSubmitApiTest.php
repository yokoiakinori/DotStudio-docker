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

class UserThumbnailSubmitApiTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->userthumbnail = factory(Userthumbnail::class)->create();
        $this->user = User::first();
    }

    /**
     * @test
     */
    public function shouldUploadFile()
    {
        Storage::fake('s3');

        $response = $this->actingAs($this->user)
            ->json('POST', route('thumbnail.update'), [
                'userthumbnail' => UploadedFile::fake()->image('thumbnail.jpg'),
            ]);
        $response->assertStatus(200);

        $userthumbnail = Userthumbnail::first();

        $this->assertMatchesRegularExpression('/^[0-9a-zA-Z-_]{12}$/', $userthumbnail->filestring);

        Storage::cloud()->assertExists($userthumbnail->filename);
    }

    /**
     * @test
     */
    public function shouldDatabaseErrorNotSaved()
    {
        Schema::drop('userthumbnails');
        Storage::fake('s3');

        $response = $this->actingAs($this->user)->json('POST', route('thumbnail.update'), [
            'userthumbnail' => UploadedFile::fake()->image('thumbnail.jpg')
        ]);

        $response->assertStatus(500);

        $this->assertEquals(0, count(Storage::cloud()->files()));
    }

    /**
     * @test
     */
    public function shouldIfErrorNotSavedDatabase()
    {
        Storage::shouldReceive('cloud')
            ->once()
            ->andReturnNull();

        $response = $this->actingAs($this->user)->json('POST', route('thumbnail.update'), [
            'userthumbnail' => UploadedFile::fake()->image('thumbnail.jpg')
        ]);

        $response->assertStatus(500);
    }
}
