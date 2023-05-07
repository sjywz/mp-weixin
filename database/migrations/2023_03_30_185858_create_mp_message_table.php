<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMpMessageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mp_message', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type')->index();
            $table->string('msgid')->nullable();
            $table->integer('create_time');
            $table->string('from');
            $table->string('appid')->index();
            $table->string('to')->index();
            $table->string('event')->nullable();
            $table->string('event_key')->nullable();
            $table->longText('content')->nullable();
            $table->longText('rest')->nullable();
            $table->string('plat_appid')->nullable();
            $table->string('reply_msgid')->nullable();
            $table->integer('sender')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mp_message');
    }
}
