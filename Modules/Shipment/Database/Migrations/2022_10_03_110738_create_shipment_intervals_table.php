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
        Schema::create('shipment_intervals', function (Blueprint $table) {
            $table->id();
            $table->foreignId("shipment_date_id")->constrained("shipment_dates")->cascadeOnDelete();
            $table->string("start_at");
            $table->string("end_at");
            $table->bigInteger("order_capacity");
            $table->decimal("shipping_cost", $precision = 64, $scale = 8);
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
        Schema::dropIfExists('shipment_intervals');
    }
};
