<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Payment\Entities\Payment;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained('users');
            $table->foreignId("payment_method_id")->constrained('payment_methods');
            $table->foreignId("gateway_id")->nullable()->constrained('gateways');
            $table->string("paymentable_type");
            $table->integer("paymentable_id");
            $table->string('invoice_id')->nullable();
            $table->string('ref_num')->nullable();
            $table->string("reference_code");
            $table->decimal('amount', $precision = 64, $scale = 8);
            $table->string("status");
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
        Schema::dropIfExists('payments');
    }
};
