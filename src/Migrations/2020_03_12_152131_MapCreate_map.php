<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MapCreateMap extends Migration
{

    const TABLE_NAME = 'mapcreate_map';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(self::TABLE_NAME, function ( Blueprint $table ){

            $table->increments( 'id' );
            $table->string('name')->unique();
            $table->integer('campaign_id')->unsigned();
            $table->integer('screen_id')->unsigned();
            $table->integer('map_imagery_id')->unsigned()->nullable();
            $table->string('screen_orientation');
            $table->string('journey_type');
            $table->string('mode')->default('fastest');
            $table->string('mid_waypoints')->nullable();
            $table->string('end_lat_waypoint');
            $table->string('end_lon_waypoint');
            $table->float('walkspeed')->default(1.25);
            $table->integer('override_rotation')->unsigned()->nullable();
            $table->integer('map_result_id')->unsigned()->nullable();
            $table->boolean('enable_traffic')->default(0);
            $table->boolean('enable_auto_rotation')->default(1);
            $table->boolean('enable_map_create')->default(0);

            $table->timestamps();
            $table->softDeletes();

            $table->foreign( 'campaign_id' )->references( 'id' )->on( 'campaigns' )->onDelete( 'CASCADE' );
            $table->foreign( 'screen_id' )->references( 'id' )->on( 'screens' )->onDelete( 'CASCADE' );
            $table->foreign( 'map_imagery_id' )->references( 'id' )->on( 'Map_map_imagery' )->onDelete( 'CASCADE' );
            $table->foreign( 'map_result_id' )->references( 'id' )->on( 'Map_map' )->onDelete( 'CASCADE' );



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
