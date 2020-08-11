<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOwnerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('owners', function (Blueprint $table) {
            $table->string('id_user');
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
            $table->string('id_store');
            $table->foreign('id_store')->references('id_store')->on('stores')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['id_user', 'id_store']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('owners');
    }
}
