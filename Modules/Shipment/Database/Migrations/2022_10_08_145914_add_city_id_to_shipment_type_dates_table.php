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
        Schema::table('shipment_type_dates', function (Blueprint $table) {
            $table->foreignId("city_id")->after('shipment_type_id')->constrained("cities")->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shipment_type_dates', function (Blueprint $table) {
            $table->dropColumn("city_id");
        });
    }
};
