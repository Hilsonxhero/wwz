<?php

use Modules\Banner\Entities\Banner;
use Modules\Banner\Enum\BannerStatus;
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
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('url');
            $table->string('type');
            $table->integer('bannerable_id');
            $table->string('bannerable_type');
            $table->integer('position');
            $table->string('status')->default(BannerStatus::ENABLE->value);
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
        Schema::dropIfExists('banners');
    }
};
