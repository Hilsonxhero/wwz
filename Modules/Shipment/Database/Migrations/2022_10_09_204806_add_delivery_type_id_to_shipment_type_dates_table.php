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
            $table->foreignId("delivery_type_id")->nullable()->after("shipment_type_id")->constrained("delivery_types");
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
            $table->dropForeign(['delivery_type_id']);
            $table->dropColumn('delivery_type_id');
        });
    }
};
