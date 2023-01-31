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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->decimal('value', 18, 4)->unsigned();
            $table->decimal('minimum_spend', 18, 4)->unsigned();
            $table->decimal('maximum_spend', 18, 4)->unsigned();
            $table->integer('usage_limit_per_voucher');
            $table->integer('usage_limit_per_customer');
            $table->integer('used')->default(0);
            $table->boolean('is_percent')->default(FALSE);
            $table->boolean('is_active')->default(FALSE);
            $table->timestamp('start_date');
            $table->timestamp('end_date');
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
        Schema::dropIfExists('vouchers');
    }
};
