<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Product\Entities\FeatureValue;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_features', function (Blueprint $table) {
            $table->id();
            $table->foreignId('feature_id')->constrained('features')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('feature_value_id')->nullable()->constrained('feature_values')->onDelete('cascade');
            $table->text('value')->nullable();
            // $table->primary(['product_id', 'feature_value_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_features');
    }
};
