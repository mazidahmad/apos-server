<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->string('id_user')->primary();
            $table->string('name_user');
            $table->string('username')->unique();
            $table->string('email_user')->unique();
            $table->string('password_user');
            $table->string('phone_user');
            $table->string('photo_user')->nullable();
            $table->enum('status',['activated','unactivated']);
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
