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
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCategoryExample()
    {
        $category = Category::factory(3)->create();

        $response = $this->get('/api/v1/application/categories');

        $response->assertStatus(200);

        // $response->assertJsonFragment(['title' => $category->title]);

    }

    /**
     * create  category.
     *
     * @return void
     */
    public function testCreateCategory()
    {

        config()->set('medialibrary.disk_name', 'testing');

        $categoryData = [
            'title' => 'New Category',
            'title_en' => 'New Category (English)',
            'slug' => 'new-category',
            'link' => 'https://example.com',
            'description' => 'This is a new category.',
            'status' => CategoryStatus::ENABLE->value,
            'image' => \Illuminate\Http\Testing\File::image('photo.jpg')

        ];

        $this->createUser();

        $response = $this->post('/api/v1/panel/categories', $categoryData);

        $response->assertStatus(200);
    }

    /**
     * create  category.
     *
     * @return void
     */
    public function testUserCanNotAccessCreateCategory()
    {

        config()->set('medialibrary.disk_name', 'testing');

        $categoryData = [
            'title' => 'New Category',
            'title_en' => 'New Category (English)',
            'slug' => 'new-category',
            'link' => 'https://example.com',
            'description' => 'This is a new category.',
            'status' => CategoryStatus::ENABLE->value,
            'image' => \Illuminate\Http\Testing\File::image('photo.jpg')
        ];

        $response = $this->post('/api/v1/panel/categories', $categoryData);

        $response->assertStatus(401);
    }

    /**
     * update  category.
     *
     * @return void
     */
    public function testUpdateCategory()
    {
        $category = Category::factory()->create();

        config()->set('medialibrary.disk_name', 'testing');

        $updatedCategoryData = [
            'title' => 'Updated Category',
            'title_en' => 'Updated Category (English)',
            'slug' => 'updated-category',
            'link' => 'https://example.com/updated-category',
            'description' => 'This category has been updated.',
            'status' => CategoryStatus::DISABLE->value,
            'image' => \Illuminate\Http\Testing\File::image('photo.jpg')
        ];

        $this->createUser();

        $response = $this->put('/api/v1/panel/categories/' . $category->id, $updatedCategoryData);

        $response->assertStatus(200);
    }


    /**
     * delete  category.
     *
     * @return void
     */
    public function testDeleteCategory()
    {
        $category = Category::factory()->create();

        $this->createUser();

        $response = $this->delete('/api/v1/panel/categories/' . $category->id);

        $response->assertStatus(200);
    }


    public function createUser()
    {
        Passport::actingAs(
            User::factory()->create(),
        );
    }
}
