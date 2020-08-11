<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->string('id_employee')->primary();
            $table->string('id_outlet')->nullable()->unsigned();
            $table->foreign('id_outlet')->references('id_outlet')->on('outlets')->nullable()->onDelete('cascade');
            $table->string('id_user')->unique()->nullable()->unsigned();
            $table->foreign('id_user')->references('id_user')->on('users');
            $table->string('name_employee');
            $table->enum('role',['admin','supervisor','operator']);
            $table->enum('status',['hired','fired','resigned']);
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
        Schema::dropIfExists('employees');
    }
}
