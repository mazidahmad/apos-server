<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegistervoucherTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('register_vouchers', function (Blueprint $table) {
            $table->string('id_sales');
            $table->foreign('id_sales')->references('id_sales')->on('sales')->onDelete('cascade');
            $table->string('id_voucher');
            $table->foreign('id_voucher')->references('id_voucher')->on('vouchers')->onDelete('cascade');            
            $table->integer('total_disc');
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
        Schema::dropIfExists('register_vouchers');
    }
}
