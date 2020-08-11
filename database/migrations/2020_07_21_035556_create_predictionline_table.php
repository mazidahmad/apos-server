<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePredictionlineTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prediction_lines', function (Blueprint $table) {
            $table->integer('periode');
            $table->date('start_periode_date');
            $table->date('end_periode_date');
            $table->string('id_outlet_menu');
            $table->foreign('id_outlet_menu')->references('id_outlet_menu')->on('outlet_menus')->onDelete('cascade');
            $table->integer('sales_qty')->nullable();
            $table->double('wma_1')->nullable();
            $table->double('error_1')->nullable();
            $table->double('presentation_error_1')->nullable();
            $table->double('wma_2')->nullable();
            $table->double('error_2')->nullable();
            $table->double('presentation_error_2')->nullable();
            $table->double('wma_3')->nullable();
            $table->double('error_3')->nullable();
            $table->double('presentation_error_3')->nullable();
            $table->double('wma_4')->nullable();
            $table->double('error_4')->nullable();
            $table->double('presentation_error_4')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prediction_lines');
    }
}
