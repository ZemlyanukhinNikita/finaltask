<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_transfers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('sender_id');
            $table->unsignedInteger('receiver_id');
            $table->unsignedDecimal('amount');
            $table->unsignedInteger('status_id');
            $table->dateTime('scheduled_time');
            $table->timestamps();
        });

        Schema::table('users_transfers', function ($table) {
            $table->foreign('status_id')->references('id')->on('statuses');
            $table->foreign('sender_id')->references('id')->on('users');
            $table->foreign('receiver_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_transfers');
    }
}
