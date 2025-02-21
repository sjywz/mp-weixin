<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlatformEventTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('platform_event', function (Blueprint $table) {
            $table->increments('id');
            $table->string('appid');
            $table->integer('create_time');
            $table->string('info_type');
            $table->string('plat_appid');
            $table->longText('rest')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('platform_event');
    }
}
