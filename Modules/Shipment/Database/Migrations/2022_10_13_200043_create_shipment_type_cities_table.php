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
        Schema::create('shipment_type_cities', function (Blueprint $table) {
            $table->id();
            $table->foreignId("shipment_type_id")->constrained("shipment_types");
            $table->foreignId("delivery_type_id")->constrained('delivery_types');
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
        Schema::dropIfExists('shipment_type_cities');
    }
};
