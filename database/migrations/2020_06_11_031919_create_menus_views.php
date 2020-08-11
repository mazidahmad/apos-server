<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateMenusViews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
        CREATE VIEW views_menus AS
        (
            SELECT om.id_outlet_menu as id_outlet_menu, om.id_outlet as id_outlet, m.name_menu as name_menu,
            m.category as category, m.description as description, om.cog AS cog, 
            om.price AS price, om.stock AS stock, m.photo_menu as photo_menu, om.is_available

            FROM outlet_menus om
            LEFT JOIN menus m ON m.id_menu=om.id_menu
        )
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP VIEW IF EXISTS views_menus');
    }
}
