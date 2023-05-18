<?php

namespace Modules\Product\Tests\Feature;

use Tests\TestCase;
use Laravel\Passport\Passport;
use Modules\User\Entities\User;
use Modules\Seller\Entities\Seller;
use Modules\Product\Entities\Product;
use Modules\Shipment\Entities\Shipment;
use Modules\Warranty\Entities\Warranty;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Product\Entities\ProductVariant;
use Modules\Product\Entities\Variant;

class ProductVariantTest extends TestCase
{

    use RefreshDatabase;

    public $product_variant_data;

    public function setUp(): void
    {
        parent::setUp();

        $this->product_variant_data =  [
            'product_id' => Product::factory()->create()->first()->id,
            'seller_id' => Seller::factory()->create()->first()->id,
            'warranty_id' => Warranty::factory()->create()->first()->id,
            'shipment_id' => Shipment::factory()->create()->first()->id,
            'price' => random_int(10000, 999999999),
            'discount' => random_int(1, 100),
            'discount_price' => random_int(10000, 999999999),
            'stock' => random_int(1, 300),
            'weight' => random_int(100, 3000),
            'order_limit' => random_int(2, 8),
            'default_on' => true,
            'discount_expire_at' => null,
            'combinations' => Variant::factory()->count(2)->create()->pluck('id')
        ];
    }


    /**
     * Test to get product variants
     *
     * @return void
     */
    public function testGetProductVariantColection()
    {
        $variants = ProductVariant::factory(3)->create();

        $this->createUser();

        $product = Product::factory()->create()->first();

        $response = $this->getJson("/api/v1/panel/products/{$product->id}/variants");

        $response->assertStatus(200);
    }



    /**
     * Test to create product variant.
     *
     * @return void
     */
    public function testCreateProductVariant()
    {
        $this->createUser();

        $product = Product::factory()->create()->first();

        $response = $this->postJson("/api/v1/panel/products/{$product->id}/variants", $this->product_variant_data);

        $response->assertStatus(200);
    }



    /**
     * Test to guest user cannot access create a product variant.
     *
     * @return void
     */
    public function testGuestUserCanNotAccessCreateProductVariant()
    {
        $product = Product::factory()->create()->first();

        $response = $this->postJson("/api/v1/panel/products/{$product->id}/variants", $this->product_variant_data);

        $response->assertStatus(401);
    }

    /**
     * Test to update a product variant
     *
     * @return void
     */
    public function testUpdateProductVariant()
    {
        $variant = ProductVariant::factory()->create();

        $this->createUser();

        $product = Product::factory()->create()->first();

        $response = $this->putJson("/api/v1/panel/products/{$product->id}/variants/" . $variant->id, $this->product_variant_data);

        $response->assertStatus(200);
    }


    /**
     * Test to check product variant validation errors.
     *
     * @return void
     */
    public function testUpdateProductVariantValidationError()
    {
        $variant = ProductVariant::factory()->create();

        $this->createUser();

        $product = Product::factory()->create()->first();

        $response = $this->putJson("/api/v1/panel/products/{$product->id}/variants/{$variant->id}", [...$this->product_variant_data, 'price' => null]);

        $response->assertStatus(422);
    }


    /**
     * Test to delete a product variant
     *
     * @return void
     */
    public function testDeleteProductVariant()
    {
        $variant = ProductVariant::factory()->create();

        $this->createUser();

        $product = Product::factory()->create()->first();

        $response = $this->deleteJson("/api/v1/panel/products/{$product->id}/variants/{$variant->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('product_variants', $variant->toArray());
    }

    public function createUser()
    {
        Passport::actingAs(
            User::factory()->create(),
        );
    }
}
