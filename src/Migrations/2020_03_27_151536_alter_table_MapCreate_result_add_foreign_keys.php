<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableMapCreateResultAddForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mapcreate_map_results', function (Blueprint $table) {

            $table->integer('map_options_id')->unsigned()->nullable();
            $table->integer('map_imagery_id')->unsigned()->nullable();

            $table->foreign('map_options_id')->references('id')->on('Map_map');
            $table->foreign('map_imagery_id')->references('id')->on('Map_map_imagery');

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
