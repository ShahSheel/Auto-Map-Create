<?php

namespace Sheel\Map\Listeners;

use Sheel\Map\Events\MapCreateMapDeletedEvent;
use Sheel\Map\Models\MapCreateMapImagery;
use Sheel\Map\Models\MapCreateMapResults;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class MapCreateDeletedListener {

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
     * @param  MapCreateMapDeletedEvent $event
     * @return void
     */
    public function handle(MapCreateMapDeletedEvent $event ) {

        // Loop through the map results
        $event->Map->MapMapResults()->each( function(MapCreateMapResults $mapResults ) {

            $mapResults->delete();

        } );




    }

}