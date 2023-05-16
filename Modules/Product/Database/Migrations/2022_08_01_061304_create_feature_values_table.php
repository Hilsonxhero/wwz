<?php

use Illuminate\Support\Facades\Schema;
use Modules\Product\Enums\FeatureStatus;
use Illuminate\Database\Schema\Blueprint;
use Modules\Product\Entities\FeatureValue;
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
        Schema::create('feature_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('feature_id')->constrained('features')->onDelete('cascade');
            $table->string('title');
            $table->string('status')->default(FeatureStatus::ENABLE->value);
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
        Schema::dropIfExists('feature_values');
    }
};
