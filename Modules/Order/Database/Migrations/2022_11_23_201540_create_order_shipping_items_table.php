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
        Schema::create('order_shipping_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products');
            $table->foreignId('variant_id')->constrained('product_variants');
            $table->foreignId('order_shipping_id')->constrained('order_shippings')->cascadeOnDelete();
            $table->unsignedBigInteger('quantity');
            $table->unsignedBigInteger('returned_quantity')->default(0);
            $table->unsignedBigInteger('cancelled_quantity')->default(0);
            $table->text('price');
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
        Schema::dropIfExists('order_shipping_items');
    }
};
