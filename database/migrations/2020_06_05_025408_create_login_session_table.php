<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoginSessionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('login_sessions', function (Blueprint $table) {
            $table->string('id_session')->primary();
            $table->string('id_user');
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
            $table->string('id_employee');
            $table->foreign('id_employee')->references('id_employee')->on('employees')->onDelete('cascade');
            $table->boolean('is_active');
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
        Schema::dropIfExists('login_sessions');
    }
}
