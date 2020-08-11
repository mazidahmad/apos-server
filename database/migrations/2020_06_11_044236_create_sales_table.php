<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->string('id_sales')->primary();
            $table->string('id_outlet');
            $table->foreign('id_outlet')->references('id_outlet')->on('outlets')->onDelete('cascade');
            $table->string('id_employee');
            $table->foreign('id_employee')->references('id_employee')->on('employees')->onDelete('cascade');
            $table->integer('total_price');
            $table->integer('tax')->nullable();
            $table->string('customer_name')->nullable();
            $table->boolean('is_paid');
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
        Schema::dropIfExists('sales');
    }
}
