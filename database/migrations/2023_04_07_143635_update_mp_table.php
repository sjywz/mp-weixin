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
        Schema::table('mp', function (Blueprint $table) {
            $table->tinyInteger('status')->default(1)->after('created_at');
            $table->tinyInteger('account_type')->default(0)->after('created_at');
            $table->string('plat_appid', 50)->nullable()->after('created_at');
            $table->string('origin_id', 50)->nullable()->after('created_at');
            $table->string('refresh_token', 100)->nullable()->after('created_at');
            $table->string('principal_name', 100)->nullable()->after('created_at');
            $table->longText('func_info')->nullable()->after('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mp', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('plat_appid');
            $table->dropColumn('origin_id');
            $table->dropColumn('refresh_token');
            $table->dropColumn('principal_name');
            $table->dropColumn('func_info');
        });
    }
};
