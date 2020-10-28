<?php

namespace Sheel\Map\Listeners;

use Sheel\Map\Events\MapCreaterMapImageryDeletedEvent;
use Sheel\Map\Models\MapCreateMap;
use Sheel\Map\Models\MapCreateMapImagery;
use Sheel\Map\Models\MapCreateMapResults;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class MapCreateMapImageryDeletedListener {

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct() {
        //
    }

    /**
     * Handle the event.
     *
     * @param  MapCreaterMapImageryDeletedEvent $event
     * @return void
     */
    public function handle(MapCreaterMapImageryDeletedEvent $event ) {

        // Loop through the map imagery
        $event->MapImagery->MapMap()->each( function(MapCreateMap $map ) {

            $map->delete();

        } );


    }

}