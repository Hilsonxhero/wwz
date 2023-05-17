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
        Schema::create('price_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId("seller_id")->constrained('sellers')->onDelete('cascade');
            $table->foreignId("product_id")->constrained('products')->onDelete('cascade');
            $table->foreignId("product_variant_id")->constrained('product_variants')->onDelete('cascade');
            $table->decimal('price', $precision = 64, $scale = 2);
            $table->decimal('discount_price', $precision = 64, $scale = 2)->default(0);
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
        Schema::dropIfExists('price_histories');
    }
};
