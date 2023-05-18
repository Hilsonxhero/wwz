<?php

namespace Modules\User\Tests\Feature;

use Tests\TestCase;
use Laravel\Passport\Passport;
use Modules\User\Entities\User;
use Modules\Category\Entities\Category;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\User\Enums\UserStatus;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public $user_data;

    public function setUp(): void
    {
        parent::setUp();

        $this->user_data =  [
            'username' => 'sample',
            'email' => 'sample@gmail.com',
            'phone' => '09013333333',
            'national_identity_number' => random_int(10000, 1000000000),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password,
            'password_confirmation' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password,
            'status' => UserStatus::ACTIVE->value,
        ];
    }

    /**
     * Test to get users
     *
     * @return void
     */
    public function testGetUserColection()
    {
        $user = User::factory(3)->create();

        $this->createUser();

        $response = $this->getJson('/api/v1/panel/users');

        $response->assertStatus(200);
    }

    /**
     * Test to create user.
     *
     * @return void
     */
    public function testCreateUser()
    {

        config()->set('medialibrary.disk_name', 'testing');

        $this->createUser();

        $response = $this->postJson('/api/v1/panel/users', $this->user_data);

        $response->assertStatus(200);
    }

    /**
     * Test to user cannot access create a user.
     *
     * @return void
     */
    public function testUserCanNotAccessCreateUser()
    {

        config()->set('medialibrary.disk_name', 'testing');

        $response = $this->postJson('/api/v1/panel/users', $this->user_data);

        $response->assertStatus(401);
    }

    /**
     * Test to update a user
     *
     * @return void
     */
    public function testUpdateUser()
    {
        $user = User::factory()->create();

        config()->set('medialibrary.disk_name', 'testing');

        $this->createUser();

        $response = $this->putJson('/api/v1/panel/users/' . $user->id, $this->user_data);

        $response->assertStatus(200);
    }
    /**
     * Test to check user validation errors.
     *
     * @return void
     */
    public function testUpdateUserValidationError()
    {
        $user = User::factory()->create();

        $this->createUser();

        $response = $this->putJson('/api/v1/panel/users/' . $user->id, [...$this->user_data, 'username' => ""]);

        $response->assertStatus(422);
    }


    /**
     * Test to delete a user
     *
     * @return void
     */
    public function testDeleteUser()
    {
        $user = User::factory()->create();

        $this->createUser();

        $response = $this->deleteJson('/api/v1/panel/users/' . $user->id);

        $response->assertStatus(200);

        $this->assertDatabaseMissing('users', $user->toArray());
    }

    public function createUser()
    {
        Passport::actingAs(
            User::factory()->create(),
        );
    }
}
