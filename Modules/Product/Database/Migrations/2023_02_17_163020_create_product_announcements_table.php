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
        Schema::create('product_announcements', function (Blueprint $table) {
            $table->id();
            $table->foreignId("product_variant_id")->constrained('product_variants')->onDelete('cascade');
            $table->foreignId("user_id")->constrained('users')->onDelete('cascade');
            $table->string("type");
            $table->string("via_sms");
            $table->string("via_email");
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
        Schema::dropIfExists('product_announcements');
    }
};
