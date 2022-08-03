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
        Schema::create('product_variant_combination', function (Blueprint $table) {
            $table->id();
            $table->foreignId("product_variant_id")->constrained('product_variants')->onDelete('cascade');
            $table->foreignId("variant_id")->constrained('variants')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_variant_combination');
    }
};
