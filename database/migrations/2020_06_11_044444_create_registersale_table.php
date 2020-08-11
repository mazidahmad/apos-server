<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegistersaleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('register_sales', function (Blueprint $table) {
            $table->string('id_sales');
            $table->foreign('id_sales')->references('id_sales')->on('sales')->onDelete('cascade');
            $table->string('id_sales_line');
            $table->foreign('id_sales_line')->references('id_sales_line')->on('sales_line_items')->onDelete('cascade');
            $table->timestamps();         ;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('register_sales');
    }
}
