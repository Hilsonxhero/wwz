<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Article\Entities\Article;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId("category_id")->constrained('categories')->cascadeOnDelete();
            $table->string("title");
            $table->string("slug");
            $table->longText("content");
            $table->text("description");
            $table->string("status");
            $table->string("short_link")->nullable();
            $table->string("min_read")->nullable();
            $table->softDeletes();
            $table->dateTime("published_at");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
};
