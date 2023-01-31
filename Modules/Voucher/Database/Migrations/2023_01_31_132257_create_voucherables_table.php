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
        Schema::create('voucherables', function (Blueprint $table) {
            $table->id();
            $table->foreignId("voucher_id")->constrained('vouchers')->onDelete('cascade');
            $table->integer('voucherable_id');
            $table->string('voucherable_type');
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
        Schema::dropIfExists('voucherables');
    }
};
