<?php

namespace Sheel\Map\Listeners;

use Sheel\Map\Events\MapCreateMapImageryRestoredEvent;
use Sheel\Map\Models\MapCreateMap;
use Sheel\Map\Models\MapCreateMapImagery;
use Sheel\Map\Models\MapCreateMapResults;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class MapCreateMapImageryRestoredListener {

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
     * @param  MapCreateMapImageryRestoredEvent $event
     * @return void
     */
    public function handle(MapCreateMapImageryRestoredEvent $event ) {

        // Loop through the map options
        $event->MapImagery->MapMapWithTrashed()->each( function(MapCreateMapImagery $mapImagery ) {

            $mapImagery->restore();

        } );


    }

}