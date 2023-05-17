<?php

namespace Modules\Product\Tests\Feature;

use Tests\TestCase;
use Laravel\Passport\Passport;
use Modules\User\Entities\User;
use Modules\Category\Entities\Category;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Brand\Entities\Brand;
use Modules\Product\Entities\Product;
use Modules\Product\Enums\ProductStatus;
use Modules\Shipment\Entities\Delivery;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public $product_data;

    public function setUp(): void
    {
        parent::setUp();

        $this->product_data =  [
            'title_fa' => "New Product Title",
            'title_en' => "New Product Title",
            'review' => "New Product Review",
            'category_id' => Category::factory()->create()->first()->id,
            'brand_id' => Brand::factory()->create()->first()->id,
            'delivery' => Delivery::factory()->create()->first()->id,
            'status' => ProductStatus::ENABLE->value,
            'image' => \Illuminate\Http\Testing\File::image('photo.jpg')
        ];
    }

    /**
     * Test to get products
     *
     * @return void
     */
    public function testGetProductList()
    {
        $product = Product::factory(3)->create();

        $this->createUser();

        $response = $this->getJson('/api/v1/panel/products');

        $response->assertStatus(200);
    }


    /**
     * Test to create product.
     *
     * @return void
     */
    public function testCreateProduct()
    {

        config()->set('medialibrary.disk_name', 'testing');

        $this->createUser();

        $response = $this->postJson('/api/v1/panel/products', $this->product_data);

        $response->assertStatus(200);
    }

    /**
     * Test to user cannot access create a Product.
     *
     * @return void
     */
    public function testUserCanNotAccessCreateProduct()
    {

        config()->set('medialibrary.disk_name', 'testing');

        $response = $this->postJson('/api/v1/panel/products', $this->product_data);

        $response->assertStatus(401);
    }

    /**
     * Test to update a Product
     *
     * @return void
     */
    public function testUpdateProduct()
    {
        $product = Product::factory()->create();

        config()->set('medialibrary.disk_name', 'testing');

        $this->createUser();

        $response = $this->putJson('/api/v1/panel/products/' . $product->id, $this->product_data);

        $response->assertStatus(200);
    }
    /**
     * Test to check Product validation errors.
     *
     * @return void
     */
    public function testUpdateProductValidationError()
    {
        $product = Product::factory()->create();

        $this->createUser();

        $response = $this->putJson('/api/v1/panel/products/' . $product->id, [...$this->product_data, 'title_fa' => ""]);

        $response->assertStatus(422);
    }


    /**
     * Test to delete a Product
     *
     * @return void
     */
    public function testDeleteProduct()
    {
        $product = Product::factory()->create();

        $this->createUser();

        $response = $this->deleteJson('/api/v1/panel/products/' . $product->id);

        $response->assertStatus(200);

        $this->assertDatabaseMissing('products', $product->toArray());
    }


    public function createUser()
    {
        Passport::actingAs(
            User::factory()->create(),
        );
    }
}
