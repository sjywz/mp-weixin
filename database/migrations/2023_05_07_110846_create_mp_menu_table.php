<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMpMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mp_menu', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('type');
            $table->string('url')->nullable();
            $table->string('key')->nullable();
            $table->string('media_id')->nullable();
            $table->string('pagepath')->nullable();
            $table->string('article_id')->nullable();
            $table->integer('parent_id')->index()->default(0);
            $table->string('appid');
            $table->integer('mid')->index();
            $table->tinyInteger('status')->default(0);
            $table->string('group_index')->index();
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
        Schema::dropIfExists('mp_menu');
    }
}
