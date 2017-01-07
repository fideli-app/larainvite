<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvitationUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('larainvite.table_name'), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('token')->index();
            $table->string('email');
            $table->bigInteger('user_id')->unsigned();
            $table->enum('status', ['pending', 'successful','canceled','expired']);
            $table->timestamp('expired_at');
            $table->timestamp('consumed_at');
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
        Schema::drop(config('larainvite.table_name'));
    }
}
