<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOutletTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('outlets', function (Blueprint $table) {
            $table->string('id_outlet')->primary();
            $table->string('id_store');
            $table->foreign('id_store')->references('id_store')->on('stores')->onDelete('cascade');
            $table->string('name_outlet');
            $table->string('address_outlet');
            $table->string('phone_outlet')->nullable();
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
        Schema::dropIfExists('outlets');
    }
}
