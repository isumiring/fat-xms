<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_group_id');
            $table->string('username');
            $table->string('email');
            $table->string('password');
            $table->string('name');
            $table->string('avatar')->nullable();
            $table->tinyInteger('user_status');
            $table->enum('themes', ['adminlte2'])->default('adminlte2');
            $table->tinyInteger('is_superadmin')->default('0');
            $table->dateTime('last_login_at')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
