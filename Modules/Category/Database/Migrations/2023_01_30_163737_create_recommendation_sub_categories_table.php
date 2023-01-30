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
        Schema::create('recommendation_sub_categories', function (Blueprint $table) {
            $table->id();
            $table->string("title");
            $table->foreignId("main_category_id")->constrained('categories')->onDelete('cascade');
            $table->foreignId("sub_category_id")->constrained('categories')->onDelete('cascade');
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
        Schema::dropIfExists('recommendation_sub_categories');
    }
};
