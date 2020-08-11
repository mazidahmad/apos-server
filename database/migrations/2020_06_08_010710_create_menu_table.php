<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->string('id_menu')->primary();
            $table->string('id_store');
            $table->foreign('id_store')->references('id_store')->on('stores')->onDelete('cascade');
            $table->string('name_menu');
            $table->longText('description')->nullable();
            $table->enum('category',['food','drink']);
            $table->text('photo_menu')->nullable();
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
        Schema::dropIfExists('menus');
    }
}
