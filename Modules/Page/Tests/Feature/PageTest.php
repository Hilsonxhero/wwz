<?php

namespace Modules\Page\Tests\Feature;

use Tests\TestCase;
use Laravel\Passport\Passport;
use Modules\Page\Entities\Page;
use Modules\User\Entities\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PageTest extends TestCase
{
    use RefreshDatabase;

    public $page_data;

    public function setUp(): void
    {
        parent::setUp();

        $this->page_data =  [
            'title' => "New Title",
            'title_en' => "New Title",
            'content' => "New Content",
        ];
    }

    /**
     * Test to get page
     *
     * @return void
     */
    public function testGetPageColection()
    {
        $page = Page::factory(3)->create();

        $this->createUser();

        $response = $this->getJson('/api/v1/panel/pages');

        $response->assertStatus(200);
    }

    /**
     * Test to create page.
     *
     * @return void
     */
    public function testCreatePage()
    {

        $this->createUser();

        $response = $this->postJson('/api/v1/panel/pages', $this->page_data);

        $response->assertStatus(200);
    }

    /**
     * Test to user cannot access create a page.
     *
     * @return void
     */
    public function testUserCanNotAccessCreatePage()
    {
        $response = $this->postJson('/api/v1/panel/pages', $this->page_data);

        $response->assertStatus(401);
    }

    /**
     * Test to update a page
     *
     * @return void
     */
    public function testUpdatePage()
    {
        $page = Page::factory()->create();

        config()->set('medialibrary.disk_name', 'testing');

        $this->createUser();

        $response = $this->putJson('/api/v1/panel/pages/' . $page->id, $this->page_data);

        $response->assertStatus(200);
    }
    /**
     * Test to check page validation errors.
     *
     * @return void
     */
    public function testUpdatePageValidationError()
    {
        $page = Page::factory()->create();

        $this->createUser();

        $response = $this->putJson('/api/v1/panel/pages/' . $page->id, [...$this->page_data, 'title' => ""]);

        $response->assertStatus(422);
    }


    /**
     * Test to delete a page
     *
     * @return void
     */
    public function testDeletePage()
    {
        $page = Page::factory()->create();

        $this->createUser();

        $response = $this->deleteJson('/api/v1/panel/pages/' . $page->id);

        $response->assertStatus(200);

        $this->assertDatabaseMissing('pages', $page->toArray());
    }

    public function createUser()
    {
        Passport::actingAs(
            User::factory()->create(),
        );
    }
}
