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
        Schema::table('users', function (Blueprint $table) {
            $table->string('nif', 10)->after('name');
            $table->string('social_sec_num', 25)->after('email_verified_at');
            $table->integer('hours_on_contract', $autoIncrement = false)->unsigned()->after('social_sec_num');
            $table->boolean('is_admin')->after('hours_on_contract');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('nif');
            $table->dropColumn('social_sec_num');
            $table->dropColumn('hours_on_contract');
            $table->dropColumn('is_admin');
        });
    }
};
