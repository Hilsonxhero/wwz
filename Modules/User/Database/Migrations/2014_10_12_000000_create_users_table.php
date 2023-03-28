<?php

use Modules\User\Entities\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\User\Enums\UserStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('city_id')->nullable()->constrained('cities');
            $table->string('username')->nullable();
            $table->decimal('wallet', $precision = 64, $scale = 8)->default(0);
            $table->string('ip')->nullable();
            $table->integer('point')->default(0);
            $table->string('email')->nullable()->unique();
            $table->string('phone')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('status')->default(UserStatus::ACTIVE);
            $table->string('job')->nullable();
            $table->string('national_identity_number')->nullable();
            $table->string('gender')->nullable();
            $table->string('cart_number')->nullable();
            $table->string('iban')->nullable();
            $table->boolean('is_superuser')->default(0);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
