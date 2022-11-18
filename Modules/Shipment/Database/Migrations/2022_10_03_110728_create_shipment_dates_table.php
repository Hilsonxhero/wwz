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
        Schema::create('shipment_dates', function (Blueprint $table) {
            $table->id();
            $table->foreignId("shipment_id")->constrained("shipments")->cascadeOnDelete();
            $table->foreignId("shipment_city_id")->constrained("shipment_cities");
            $table->timestamp('date');
            $table->boolean('is_holiday')->default(false);
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
        Schema::dropIfExists('shipment_dates');
    }
};
