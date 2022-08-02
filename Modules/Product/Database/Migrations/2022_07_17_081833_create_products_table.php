<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Product\Entities\Product;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string("title_fa");
            $table->string("title_en");
            $table->string("slug");
            $table->longText("review");
            $table->foreignId("category_id")->constrained('categories')->onDelete('cascade');
            $table->foreignId("brand_id")->constrained('brands')->onDelete('cascade');
            $table->enum('status', Product::$statuses)->default(Product::ENABLE_STATUS);
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
        Schema::dropIfExists('products');
    }
};
