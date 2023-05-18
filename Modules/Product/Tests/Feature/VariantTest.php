<?php

namespace Modules\Product\Tests\Feature;

use Tests\TestCase;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Product\Entities\Variant;
use Modules\Product\Entities\VariantGroup;
use Modules\User\Entities\User;

class VariantTest extends TestCase
{
    use RefreshDatabase;

    public $variant_data;

    public function setUp(): void
    {
        parent::setUp();

        $this->variant_data =  [
            'name' => "New Variant",
            'variant_group_id' => VariantGroup::factory()->create()->first()->id,
            'value' => "green",
        ];
    }

    /**
     * Test to get varian.
     *
     * @return void
     */
    public function testGetVariantColection()
    {
        Variant::factory(3)->create();

        $this->createUser();

        $response = $this->getJson('/api/v1/panel/variants');

        $response->assertStatus(200);
    }


    /**
     * Test to create variant.
     *
     * @return void
     */
    public function testCreateVariant()
    {

        $this->createUser();

        $response = $this->postJson('/api/v1/panel/variants', $this->variant_data);

        $response->assertStatus(200);
    }



    /**
     * Test to user cannot access create a variant.
     *
     * @return void
     */
    public function testUserCanNotAccessCreateVariant()
    {
        $response = $this->postJson('/api/v1/panel/variants', $this->variant_data);

        $response->assertStatus(401);
    }

    /**
     * Test to update a variant
     *
     * @return void
     */
    public function testUpdateVariant()
    {
        $variant = Variant::factory()->create();

        $this->createUser();

        $response = $this->putJson('/api/v1/panel/variants/' . $variant->id, $this->variant_data);

        $response->assertStatus(200);
    }
    /**
     * Test to check variant validation errors.
     *
     * @return void
     */
    public function testUpdateVariantValidationError()
    {
        $variant = Variant::factory()->create();

        $this->createUser();

        $response = $this->putJson('/api/v1/panel/variants/' . $variant->id, [...$this->variant_data, 'name' => ""]);

        $response->assertStatus(422);
    }


    /**
     * Test to delete a variant
     *
     * @return void
     */
    public function testDeleteVariant()
    {
        $variant = Variant::factory()->create();

        $this->createUser();

        $response = $this->deleteJson('/api/v1/panel/variants/' . $variant->id);

        $response->assertStatus(200);

        $this->assertDatabaseMissing('variants', $variant->toArray());
    }


    public function createUser()
    {
        Passport::actingAs(
            User::factory()->create(),
        );
    }
}
