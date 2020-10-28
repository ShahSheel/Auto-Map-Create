<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MapCreateMapResults extends Migration
{

    const TABLE_NAME = 'mapcreate_map_results';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(self::TABLE_NAME, function ( Blueprint $table ){

            $table->increments( 'id' );
            $table->integer('distance')->nullable();
            $table->integer('baseTime')->nullable();
            $table->integer('trafficTime')->nullable();
            $table->integer('map_asset_id')->unsigned()->nullable();


            $table->timestamps();
            $table->softDeletes();


            $table->foreign('map_asset_id')->references('id')->on('assets');



        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists( self::TABLE_NAME );
    }
}
