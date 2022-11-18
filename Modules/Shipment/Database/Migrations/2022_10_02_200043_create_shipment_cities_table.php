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
        Schema::create('shipment_cities', function (Blueprint $table) {
            $table->id();
            $table->foreignId("shipment_id")->constrained("shipments");
            $table->foreignId("delivery_id")->constrained('deliveries');
            $table->foreignId("city_id")->constrained("cities");
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
        Schema::dropIfExists('shipment_cities');
    }
};
