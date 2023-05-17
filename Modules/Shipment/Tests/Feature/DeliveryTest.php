<?php

namespace Modules\Shipment\Tests\Feature;

use Tests\TestCase;
use Laravel\Passport\Passport;
use Modules\User\Entities\User;
use Modules\Shipment\Entities\Delivery;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeliveryTest extends TestCase
{
    use RefreshDatabase;

    public $delivery_data;

    public function setUp(): void
    {
        parent::setUp();

        $this->delivery_data =  [
            'title' => 'New Delivery',
            'code' => 1234,
        ];
    }

    /**
     * Test to get deliveries
     *
     * @return void
     */
    public function testGetDeliveryList()
    {
        $delivery = Delivery::factory(3)->create();

        $this->createUser();

        $response = $this->getJson('/api/v1/panel/deliveries');

        $response->assertStatus(200);
    }

    /**
     * Test to create delivery.
     *
     * @return void
     */
    public function testCreateDelivery()
    {

        config()->set('medialibrary.disk_name', 'testing');

        $this->createUser();

        $response = $this->postJson('/api/v1/panel/deliveries', $this->delivery_data);

        $response->assertStatus(200);
    }

    /**
     * Test to user cannot access create a delivery.
     *
     * @return void
     */
    public function testUserCanNotAccessCreateDelivery()
    {

        config()->set('medialibrary.disk_name', 'testing');

        $response = $this->postJson('/api/v1/panel/deliveries', $this->delivery_data);

        $response->assertStatus(401);
    }

    /**
     * Test to update a delivery
     *
     * @return void
     */
    public function testUpdateDelivery()
    {
        $delivery = Delivery::factory()->create();

        config()->set('medialibrary.disk_name', 'testing');

        $this->createUser();

        $response = $this->putJson('/api/v1/panel/deliveries/' . $delivery->id, $this->delivery_data);

        $response->assertStatus(200);
    }
    /**
     * Test to check delivery validation errors.
     *
     * @return void
     */
    public function testUpdateDeliveryValidationError()
    {
        $delivery = Delivery::factory()->create();

        $this->createUser();

        $response = $this->putJson('/api/v1/panel/deliveries/' . $delivery->id, [...$this->delivery_data, 'title' => ""]);

        $response->assertStatus(422);
    }


    /**
     * Test to delete a delivery
     *
     * @return void
     */
    public function testDeleteDelivery()
    {
        $delivery = Delivery::factory()->create();

        $this->createUser();

        $response = $this->deleteJson('/api/v1/panel/deliveries/' . $delivery->id);

        $response->assertStatus(200);

        $this->assertDatabaseMissing('deliveries', $delivery->toArray());
    }

    public function createUser()
    {
        Passport::actingAs(
            User::factory()->create(),
        );
    }
}
