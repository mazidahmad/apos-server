<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOutletmenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('outlet_menus', function (Blueprint $table) {
            $table->string('id_outlet_menu')->primary();
            $table->string('id_menu');
            $table->foreign('id_menu')->references('id_menu')->on('menus')->onDelete('cascade');
            $table->string('id_outlet');
            $table->foreign('id_outlet')->references('id_outlet')->on('outlets')->onDelete('cascade');
            $table->integer('cog')->nullable();
            $table->integer('price');
            $table->integer('stock');
            $table->boolean('is_stock');
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
        Schema::dropIfExists('outlet_menus');
    }
}
