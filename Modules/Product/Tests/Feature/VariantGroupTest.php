<?php

namespace Modules\Product\Tests\Feature;

use Tests\TestCase;
use Laravel\Passport\Passport;
use Modules\User\Entities\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Product\Entities\VariantGroup;

class VariantGroupTest extends TestCase
{


    use RefreshDatabase;

    public $variant_group_data;

    public function setUp(): void
    {
        parent::setUp();

        $this->variant_group_data =  [
            'name' => "New Variant",
            'type' => "color",
            'order' => 1,
        ];
    }

    /**
     * Test to get variant group.
     *
     * @return void
     */
    public function testGetVariantGroupColection()
    {
        VariantGroup::factory(3)->create();

        $this->createUser();

        $response = $this->getJson('/api/v1/panel/variant/groups');

        $response->assertStatus(200);
    }


    /**
     * Test to create variant group.
     *
     * @return void
     */
    public function testCreateVariantGroup()
    {

        $this->createUser();

        $response = $this->postJson('/api/v1/panel/variant/groups', $this->variant_group_data);

        $response->assertStatus(200);
    }



    /**
     * Test to user cannot access create a variant group.
     *
     * @return void
     */
    public function testUserCanNotAccessCreateVariantGroup()
    {

        $response = $this->postJson('/api/v1/panel/variant/groups', $this->variant_group_data);

        $response->assertStatus(401);
    }

    /**
     * Test to update a variant group
     *
     * @return void
     */
    public function testUpdateVariantGroup()
    {
        $variant_group = VariantGroup::factory()->create();

        $this->createUser();

        $response = $this->putJson('/api/v1/panel/variant/groups/' . $variant_group->id, $this->variant_group_data);

        $response->assertStatus(200);
    }
    /**
     * Test to check variant group validation errors.
     *
     * @return void
     */
    public function testUpdateVariantGroupValidationError()
    {
        $variant_group = VariantGroup::factory()->create();

        $this->createUser();

        $response = $this->putJson('/api/v1/panel/variant/groups/' . $variant_group->id, [...$this->variant_group_data, 'name' => ""]);

        $response->assertStatus(422);
    }


    /**
     * Test to delete a variant group
     *
     * @return void
     */
    public function testDeleteVariantGroup()
    {
        $variant_group = VariantGroup::factory()->create();

        $this->createUser();

        $response = $this->deleteJson('/api/v1/panel/variant/groups/' . $variant_group->id);

        $response->assertStatus(200);

        $this->assertDatabaseMissing('variant_groups', $variant_group->toArray());
    }


    public function createUser()
    {
        Passport::actingAs(
            User::factory()->create(),
        );
    }
}
