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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained('users')->cascadeOnDelete();
            $table->foreignId("state_id")->constrained('states')->cascadeOnDelete();
            $table->foreignId("city_id")->constrained('cities')->cascadeOnDelete();
            $table->text("address");
            $table->string("postal_code");
            $table->string("telephone")->nullable();
            $table->string("mobile");
            $table->boolean("is_default")->default(false);
            $table->string("latitude")->nullable();
            $table->string("longitude")->nullable();
            $table->string("building_number");
            $table->string("unit");
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
        Schema::dropIfExists('addresses');
    }
};
