<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Order\Enums\OrderStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('cart_id')->constrained('carts');
            $table->foreignId('payment_method_id')->constrained('payment_methods');
            $table->string('reference_code');
            $table->string('status');
            $table->timestamp('payment_remaining_time')->nullable();
            $table->timestamp('returnable_until')->nullable();
            $table->decimal('remaining_amount', $precision = 64, $scale = 8)->nullable();
            $table->decimal('payable_price', $precision = 64, $scale = 8);
            $table->boolean('is_returnable')->default(false);
            $table->text('price');
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
        Schema::dropIfExists('orders');
    }
};
