<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained('users')->onDelete('cascade');
            $table->foreignId("comment_id")->nullable()->constrained('comments')->cascadeOnDelete();
            $table->integer('commentable_id');
            $table->string('commentable_type');
            $table->string('title');
            $table->longText('body');
            $table->integer('like');
            $table->integer('dislike');
            $table->integer('report');
            $table->string('status');
            $table->boolean('is_anonymous');
            $table->boolean('is_buyer');
            $table->boolean('is_recommendation');
            $table->text('advantages');
            $table->text('disadvantages');
            $table->softDeletes();
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
        Schema::dropIfExists('comments');
    }
};
