<?php

namespace Modules\Banner;

use Tests\TestCase;
use Laravel\Passport\Passport;
use Modules\Page\Entities\Page;
use Modules\User\Entities\User;
use Modules\Banner\Entities\Banner;
use Modules\Banner\Enum\BannerStatus;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BannerTest extends TestCase
{
    use RefreshDatabase;

    public $banner_data;

    public function setUp(): void
    {
        parent::setUp();

        $this->banner_data =  [
            'title' => "New Title",
            'url' => "https://example.com",
            'type' => "top",
            'position' => 1,
            'page' => Page::factory()->create()->first()->id,
            'status' => BannerStatus::ENABLE->value,
            'banner' => \Illuminate\Http\Testing\File::image('photo.jpg'),
            'mobile_banner' => \Illuminate\Http\Testing\File::image('photo.jpg'),
        ];
    }

    /**
     * Test to get banner
     *
     * @return void
     */
    public function testGetBannerColection()
    {
        $banner = Banner::factory(3)->create();

        $this->createUser();

        $response = $this->getJson('/api/v1/panel/setting/banners');

        $response->assertStatus(200);
    }

    /**
     * Test to create banner.
     *
     * @return void
     */
    public function testCreateBanner()
    {

        $this->createUser();

        $response = $this->postJson("/api/v1/panel/setting/banners", $this->banner_data);

        $response->assertStatus(200);
    }

    /**
     * Test to user cannot access create a banner.
     *
     * @return void
     */
    public function testUserCanNotAccessCreateBanner()
    {
        $response = $this->postJson("/api/v1/panel/setting/banners", $this->banner_data);

        $response->assertStatus(401);
    }

    /**
     * Test to update a banner
     *
     * @return void
     */
    public function testUpdateBanner()
    {
        $banner = Banner::factory()->create();

        config()->set('medialibrary.disk_name', 'testing');

        $this->createUser();

        $response = $this->putJson('/api/v1/panel/setting/banners/' . $banner->id, $this->banner_data);

        $response->assertStatus(200);
    }
    /**
     * Test to check banner validation errors.
     *
     * @return void
     */
    public function testUpdateBannerValidationError()
    {
        $banner = Banner::factory()->create();

        $this->createUser();

        $response = $this->putJson('/api/v1/panel/setting/banners/' . $banner->id, [...$this->banner_data, 'title' => ""]);

        $response->assertStatus(422);
    }


    /**
     * Test to delete a banner
     *
     * @return void
     */
    public function testDeleteBanner()
    {
        $banner = Banner::factory()->create();

        $this->createUser();

        $response = $this->deleteJson('/api/v1/panel/setting/banners/' . $banner->id);

        $response->assertStatus(200);

        $this->assertDatabaseMissing('banners', $banner->toArray());
    }

    public function createUser()
    {
        Passport::actingAs(
            User::factory()->create(),
        );
    }
}
