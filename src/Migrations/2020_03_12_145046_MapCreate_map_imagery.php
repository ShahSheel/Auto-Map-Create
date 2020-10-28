<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MapCreateMapImagery extends Migration
{

    const TABLE_NAME = 'mapcreate_map_imagery';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(self::TABLE_NAME, function ( Blueprint $table ){

            $table->increments( 'id' );
            $table->string('name');
            $table->integer('map_width')->unsigned();
            $table->integer('map_height')->unsigned();
            $table->string('line_color')->default('blue');
            $table->string('line_border_color')->default('blue');

            $table->timestamps();
            $table->softDeletes();





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
