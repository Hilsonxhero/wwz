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
        Schema::create('shipment_type_dates', function (Blueprint $table) {
            $table->id();
            $table->foreignId("shipment_type_id")->constrained("shipment_types")->cascadeOnDelete();
            $table->timestamp('date');
            $table->string("weekday_name");
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
        Schema::dropIfExists('shipment_type_dates');
    }
};
