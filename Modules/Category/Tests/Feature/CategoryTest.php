<?php

namespace Modules\Category\Tests\Feature;

use Tests\TestCase;
use Laravel\Passport\Passport;
use Modules\User\Entities\User;
use Modules\Category\Entities\Category;
use Modules\Category\Enum\CategoryStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public $category_data;

    public $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->category_data =  [
            'title' => 'New Category',
            'title_en' => 'New Category (English)',
            'slug' => 'new-category',
            'link' => 'https://example.com',
            'description' => 'This is a new category.',
            'status' => CategoryStatus::ENABLE->value,
            'image' => \Illuminate\Http\Testing\File::image('photo.jpg')
        ];

        // $this->createUser();
    }


    /**
     * Test to get categories
     *
     * @return void
     */
    public function testGetCategoryList()
    {
        $category = Category::factory(3)->create();

        $response = $this->getJson('/api/v1/application/categories');

        $response->assertStatus(200);
    }

    /**
     * Test to create category.
     *
     * @return void
     */
    public function testCreateCategory()
    {

        config()->set('medialibrary.disk_name', 'testing');

        $this->createUser();

        $response = $this->postJson('/api/v1/panel/categories', $this->category_data);

        $response->assertStatus(200);
    }

    /**
     * Test to user cannot access create a category.
     *
     * @return void
     */
    public function testUserCanNotAccessCreateCategory()
    {

        config()->set('medialibrary.disk_name', 'testing');

        $response = $this->postJson('/api/v1/panel/categories', $this->category_data);

        $response->assertStatus(401);
    }

    /**
     * Test to update a category
     *
     * @return void
     */
    public function testUpdateCategory()
    {
        $category = Category::factory()->create();

        config()->set('medialibrary.disk_name', 'testing');

        $this->createUser();

        $response = $this->putJson('/api/v1/panel/categories/' . $category->id, $this->category_data);

        $response->assertStatus(200);
    }
    /**
     * Test to check category validation errors.
     *
     * @return void
     */
    public function testUpdateCategoryValidationError()
    {
        $category = Category::factory()->create();

        $this->createUser();

        $response = $this->putJson('/api/v1/panel/categories/' . $category->id, [...$this->category_data, 'title' => ""]);

        $response->assertStatus(422);
    }


    /**
     * Test to delete a category
     *
     * @return void
     */
    public function testDeleteCategory()
    {
        $category = Category::factory()->create();

        $this->createUser();

        $response = $this->deleteJson('/api/v1/panel/categories/' . $category->id);

        $response->assertStatus(200);

        $this->assertDatabaseMissing('categories', $category->toArray());
    }


    public function createUser()
    {
        Passport::actingAs(
            User::factory()->create(),
        );
    }
}
