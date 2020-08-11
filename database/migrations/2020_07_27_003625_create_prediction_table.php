<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePredictionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('predictions', function (Blueprint $table) {
            $table->integer('periode');
            $table->string('id_outlet_menu');
            $table->string('name_menu');
            $table->foreign('id_outlet_menu')->references('id_outlet_menu')->on('outlet_menus')->onDelete('cascade');
            $table->double('wma')->nullable();
            $table->double('mape')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('predictions');
    }
}
