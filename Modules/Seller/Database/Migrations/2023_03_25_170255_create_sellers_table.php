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
        Schema::create('sellers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('state_id')->nullable()->constrained('states');
            $table->foreignId('city_id')->nullable()->constrained('cities');
            $table->string('name')->nullable();
            $table->string('lname')->nullable();
            $table->string("title");
            $table->string('brand_name')->nullable();
            $table->string("code");
            $table->string("shaba_number");
            $table->string("postal_code");
            $table->string('job')->nullable();
            $table->string('national_identity_number')->nullable();
            $table->string('ip')->nullable();
            $table->string('email')->nullable()->unique();
            $table->string('phone')->unique();
            $table->string('password');
            $table->longText('about');
            $table->string('website')->nullable();
            $table->string('telephone')->nullable();
            $table->string('status');
            $table->string('birth')->nullable();
            $table->string('learning_status')->nullable();
            $table->decimal('wallet',  64,  3)->default(0);
            $table->boolean('is_default')->default(0);
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
        Schema::dropIfExists('sellers');
    }
};
