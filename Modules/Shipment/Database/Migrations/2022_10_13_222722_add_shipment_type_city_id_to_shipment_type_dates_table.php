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
            $table->foreignId("shipment_type_city_id")->after('id')->constrained("shipment_type_cities");
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
            $table->dropForeign(['shipment_type_city_id']);
            $table->dropColumn("shipment_type_city_id");
        });
    }
};
