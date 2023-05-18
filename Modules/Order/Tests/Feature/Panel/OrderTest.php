<?php

namespace Modules\Order\Tests\Feature\Panel;

use Tests\TestCase;
use Laravel\Passport\Passport;
use Modules\User\Entities\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Order\Entities\Order;
use Modules\Order\Enums\OrderStatus;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public $order_data;

    public function setUp(): void
    {
        parent::setUp();

        $this->order_data =  [
            'status' => OrderStatus::WaitPayment->value
        ];
    }


    /**
     * Test to get orders
     *
     * @return void
     */
    public function testGetOrderColection()
    {
        $orders = Order::factory(3)->create();

        $this->createUser();

        $response = $this->getJson('/api/v1/panel/orders');

        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'links', 'meta'])
            ->assertJsonCount(3, 'data');
    }

    /**
     * Test to get single order
     *
     * @return void
     */
    public function testGetOrder()
    {
        $orders = Order::factory(3)->create();

        $this->createUser();

        $response = $this->getJson('/api/v1/panel/orders');

        $response->assertStatus(200)->assertJsonStructure([
            'data' => [
                'id',
            ]
        ]);
    }

    /**
     * Test to guest user cannot access create a user.
     *
     * @return void
     */
    public function testGuestUserCanNotAccessCreateUser()
    {

        $response = $this->postJson('/api/v1/panel/orders', $this->order_data);

        $response->assertStatus(401);
    }

    public function createUser()
    {
        Passport::actingAs(
            User::factory()->create(),
        );
    }
}
