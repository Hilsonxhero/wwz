<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Product\Entities\Feature;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('features', function (Blueprint $table) {
            $table->id();
            $table->string("title");
            $table->foreignId("category_id")->constrained('categories')->onDelete('cascade');
            $table->foreignId("parent_id")->nullable()->constrained('features')->onDelete('cascade');
            $table->integer("position")->default(0);
            $table->enum('status', Feature::$statuses)->default(Feature::ENABLE_STATUS);
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
        Schema::dropIfExists('features');
    }
};
