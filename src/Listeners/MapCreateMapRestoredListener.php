<?php

namespace Sheel\Map\Listeners;

use Sheel\Map\Events\MapCreateMapRestoredEvent;
use Sheel\Map\Models\MapCreateMap;
use Sheel\Map\Models\MapCreateMapImagery;
use Sheel\Map\Models\MapCreateMapResults;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class MapCreateMapRestoredListener {

    /* Create the event listener.
    *
    * @return void
    */
    public function __construct() {
        //
    }

    /**
     * Handle the event.
     *
     * @param  MapCreateMapRestoredEvent $event
     * @return void
     */
    public function handle(MapCreateMapRestoredEvent $event ) {

        // Loop through the map results
        $event->Map->MapMapResultsWithTrashed()->each( function(MapCreateMap $map ) {

            $map->restore();

        } );

    }

}