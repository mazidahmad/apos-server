<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustommenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_menus', function (Blueprint $table) {
            $table->string('id_custom_menu')->primary();
            $table->string('id_outlet');
            $table->foreign('id_outlet')->references('id_outlet')->on('outlets')->onDelete('cascade');
            $table->integer('price');
            $table->string('description');
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
        Schema::dropIfExists('custom_menus');
    }
}
