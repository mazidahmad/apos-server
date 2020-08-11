<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesLineItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_line_items', function (Blueprint $table) {
            $table->string('id_sales_line')->primary();
            $table->string('id_outlet_menu')->nullable()->unsigned();
            $table->foreign('id_outlet_menu')->references('id_outlet_menu')->on('outlet_menus')->onDelete('cascade');
            $table->string('id_custom_menu')->nullable()->unsigned();
            $table->foreign('id_custom_menu')->references('id_custom_menu')->on('custom_menus')->onDelete('cascade');
            $table->integer('quantity');
            $table->integer('discount');
            $table->integer('subtotal_price');            
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
        Schema::dropIfExists('sales_line_items');
    }
}
