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
        Schema::create('cart_shippings', function (Blueprint $table) {
            $table->id();
            $table->foreignId("shipping_id")->constrained('shippings')->cascadeOnDelete();
            $table->foreignId("cart_item_id")->constrained('cart_items')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cart_shippings');
    }
};
