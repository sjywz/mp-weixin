<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('material', function (Blueprint $table) {
            $table->id();
            $table->string('media_id');
            $table->string('name')->nullable();
            $table->string('url')->nullable();
            $table->string('content')->nullable();
            $table->integer('is_temp')->default(0);
            $table->string('type')->nullable();
            $table->string('appid');
            $table->integer('mid')->index()->default(0);
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
        Schema::dropIfExists('material');
    }
};
