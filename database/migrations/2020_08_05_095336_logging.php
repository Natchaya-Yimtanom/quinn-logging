<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Logging extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logging', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('user');
            $table->string('level')->index();
            $table->string('level_name');
            $table->longText('message');
            $table->longText('stack')->nullable();
            $table->string('channel')->index();
            $table->string('date');
            $table->string('time');
            $table->longText('context');
            $table->longText('extra');
            $table->string('remote_addr')->nullable();
            $table->string('user_agent')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
