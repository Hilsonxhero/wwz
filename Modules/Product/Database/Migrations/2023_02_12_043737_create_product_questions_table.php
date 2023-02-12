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
        Schema::create('product_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained('users')->onDelete('cascade');
            $table->foreignId("product_id")->constrained('products')->onDelete('cascade');
            $table->foreignId("product_question_id")->nullable()->constrained('product_questions')->cascadeOnDelete();
            $table->longText('content');
            $table->integer('like');
            $table->integer('dislike');
            $table->integer('report');
            $table->string('status');
            $table->boolean('is_anonymous');
            $table->boolean('is_buyer');
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
        Schema::dropIfExists('product_questions');
    }
};
