<?php

namespace Modules\Brand\Tests\Feature;

use Tests\TestCase;
use Laravel\Passport\Passport;
use Modules\User\Entities\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Brand\Entities\Brand;
use Modules\Brand\Enum\BrandStatus;

class BrandTest extends TestCase
{
    use RefreshDatabase;

    public $brand_data;

    public function setUp(): void
    {
        parent::setUp();

        $this->brand_data =  [
            'title' => 'New Brand',
            'title_en' => 'New Brand (English)',
            'slug' => 'new-brand',
            'link' => 'https://example.com',
            'description' => 'This is a new brand.',
            'brand_id' => Brand::factory()->create()->first()->id,
            'status' => BrandStatus::ENABLE->value,
            'is_special' => false,
            'logo' => \Illuminate\Http\Testing\File::image('photo.jpg')
        ];
    }

    /**
     * Test to get brands
     *
     * @return void
     */
    public function testGetBrandList()
    {
        $brand = Brand::factory(3)->create();

        $response = $this->getJson('/api/v1/application/brands');

        $response->assertStatus(200);
    }

    /**
     * Test to create brand.
     *
     * @return void
     */
    public function testCreateBrand()
    {

        config()->set('medialibrary.disk_name', 'testing');

        $this->createUser();

        $response = $this->postJson('/api/v1/panel/brands', $this->brand_data);

        $response->assertStatus(200);
    }

    /**
     * Test to user cannot access create a brand.
     *
     * @return void
     */
    public function testUserCanNotAccessCreateBrand()
    {

        config()->set('medialibrary.disk_name', 'testing');

        $response = $this->postJson('/api/v1/panel/brands', $this->brand_data);

        $response->assertStatus(401);
    }

    /**
     * Test to update a brand
     *
     * @return void
     */
    public function testUpdateBrand()
    {
        $brand = Brand::factory()->create();

        config()->set('medialibrary.disk_name', 'testing');

        $this->createUser();

        $response = $this->putJson('/api/v1/panel/brands/' . $brand->id, $this->brand_data);

        $response->assertStatus(200);
    }
    /**
     * Test to check brand validation errors.
     *
     * @return void
     */
    public function testUpdateBrandValidationError()
    {
        $brand = Brand::factory()->create();

        $this->createUser();

        $response = $this->putJson('/api/v1/panel/brands/' . $brand->id, [...$this->brand_data, 'title' => ""]);

        $response->assertStatus(422);
    }


    /**
     * Test to delete a brand
     *
     * @return void
     */
    public function testDeleteBrand()
    {
        $brand = Brand::factory()->create();

        $this->createUser();

        $response = $this->deleteJson('/api/v1/panel/brands/' . $brand->id);

        $response->assertStatus(200);

        $this->assertDatabaseMissing('brands', $brand->toArray());
    }

    public function createUser()
    {
        Passport::actingAs(
            User::factory()->create(),
        );
    }
}
