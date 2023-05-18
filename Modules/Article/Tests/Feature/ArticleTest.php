<?php

namespace Modules\Article\Tests\Feature;

use Tests\TestCase;
use Laravel\Passport\Passport;
use Modules\User\Entities\User;
use Modules\Article\Entities\Article;
use Modules\Category\Entities\Category;
use Modules\Article\Enums\ArticleStatus;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArticleTest extends TestCase
{
    use RefreshDatabase;

    public $article_data;

    public function setUp(): void
    {
        parent::setUp();

        $this->article_data =  [
            'category_id' => Category::factory()->create()->first()->id,
            'title' => "New Title",
            'content' => "New Content",
            'description' => "New Discription",
            'status' => ArticleStatus::ENABLE->value,
            'published_at' => now(),
            'image' => \Illuminate\Http\Testing\File::image('photo.jpg')
        ];
    }

    /**
     * Test to get articles
     *
     * @return void
     */
    public function testGetArticleColection()
    {
        $article = Article::factory(3)->create();

        $this->createUser();

        $response = $this->getJson('/api/v1/panel/articles');

        $response->assertStatus(200);
    }

    /**
     * Test to create article.
     *
     * @return void
     */
    public function testCreateArticle()
    {

        config()->set('medialibrary.disk_name', 'testing');

        $this->createUser();

        $response = $this->postJson('/api/v1/panel/articles', $this->article_data);

        $response->assertStatus(200);
    }

    /**
     * Test to user cannot access create a article.
     *
     * @return void
     */
    public function testUserCanNotAccessCreateArticle()
    {

        config()->set('medialibrary.disk_name', 'testing');

        $response = $this->postJson('/api/v1/panel/articles', $this->article_data);

        $response->assertStatus(401);
    }

    /**
     * Test to update a article
     *
     * @return void
     */
    public function testUpdateArticle()
    {
        $article = Article::factory()->create();

        config()->set('medialibrary.disk_name', 'testing');

        $this->createUser();

        $response = $this->putJson('/api/v1/panel/articles/' . $article->id, $this->article_data);

        $response->assertStatus(200);
    }
    /**
     * Test to check article validation errors.
     *
     * @return void
     */
    public function testUpdateArticleValidationError()
    {
        $article = Article::factory()->create();

        $this->createUser();

        $response = $this->putJson('/api/v1/panel/articles/' . $article->id, [...$this->article_data, 'title' => ""]);

        $response->assertStatus(422);
    }


    /**
     * Test to delete a article
     *
     * @return void
     */
    public function testDeleteArticle()
    {
        $article = Article::factory()->create();

        $this->createUser();

        $response = $this->deleteJson('/api/v1/panel/articles/' . $article->id);

        $response->assertStatus(200);

        $this->assertDatabaseMissing('articles', $article->toArray());
    }

    public function createUser()
    {
        Passport::actingAs(
            User::factory()->create(),
        );
    }
}
