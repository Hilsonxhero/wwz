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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId("product_id")->constrained('products')->onDelete('cascade');
            $table->foreignId("warranty_id")->constrained('warranties')->onDelete('cascade');
            $table->decimal('price');
            $table->integer('discount');
            $table->bigInteger('discount_price');
            $table->integer('stock');
            $table->decimal('weight');
            $table->integer('order_limit');
            $table->boolean('default_on');
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
        Schema::dropIfExists('product_variants');
    }
};
