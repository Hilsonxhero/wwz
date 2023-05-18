<?php

namespace Modules\Payment\Tests\Feature\Panel;

use Tests\TestCase;
use Laravel\Passport\Passport;
use Modules\User\Entities\User;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Payment\Enums\PaymentMethodStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Payment\Entities\PaymentMethod;

class PaymentMethodTest extends TestCase
{
    use RefreshDatabase;

    public $payment_methods_data;

    public function setUp(): void
    {
        parent::setUp();

        $this->payment_methods_data =  [
            'title' => "Online",
            'slug' => "online",
            'description' => "description",
            'type' => "online",
            'status' => PaymentMethodStatus::ENABLE->value,
            'is_default' => false,
        ];
    }

    /**
     * Test to get payment methods
     *
     * @return void
     */
    public function testGetPaymentMethodColection()
    {
        $payment_method = PaymentMethod::factory(3)->create();

        $this->createUser();

        $response = $this->getJson('/api/v1/panel/payment/methods');

        $response->assertStatus(200);
    }

    /**
     * Test to create payment method.
     *
     * @return void
     */
    public function testCreatePaymentMethod()
    {

        $this->createUser();

        $response = $this->postJson('/api/v1/panel/payment/methods', $this->payment_methods_data);

        $response->assertStatus(200);
    }

    /**
     * Test to user cannot access create a payment method.
     *
     * @return void
     */
    public function testUserCanNotAccessCreatePaymentMethod()
    {
        $response = $this->postJson('/api/v1/panel/payment/methods', $this->payment_methods_data);

        $response->assertStatus(401);
    }

    /**
     * Test to update a payment method
     *
     * @return void
     */
    public function testUpdatePaymentMethod()
    {
        $payment_method = PaymentMethod::factory()->create();

        $this->createUser();

        $response = $this->putJson('/api/v1/panel/payment/methods/' . $payment_method->id, $this->payment_methods_data);

        $response->assertStatus(200);
    }
    /**
     * Test to check payment method validation errors.
     *
     * @return void
     */
    public function testUpdatePaymentMethodValidationError()
    {
        $payment_method = PaymentMethod::factory()->create();

        $this->createUser();

        $response = $this->putJson('/api/v1/panel/payment/methods/' . $payment_method->id, [...$this->payment_methods_data, 'title' => ""]);

        $response->assertStatus(422);
    }


    /**
     * Test to delete a payment method
     *
     * @return void
     */
    public function testDeletePaymentMethod()
    {
        $payment_method = PaymentMethod::factory()->create();

        $this->createUser();

        $response = $this->deleteJson('/api/v1/panel/payment/methods/' . $payment_method->id);

        $response->assertStatus(200);

        $this->assertDatabaseMissing('payment_methods', $payment_method->toArray());
    }

    public function createUser()
    {
        Passport::actingAs(
            User::factory()->create(),
        );
    }
}
