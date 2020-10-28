<?php

namespace Sheel\Map\Jobs;

use App\Screen;
use Sheel\Map\MapStore;
use Sheel\Map\Factories\MapCreateFactory;
use Sheel\Map\MapDetails\MapExt;
use Sheel\Map\MapDetails\MapImagery;
use Sheel\Map\Models\MapCreateMap;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Database\Eloquent\Collection;
use PhpParser\Node\Expr\Cast\Object_;


class GenerateStaticMap implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    CONST WAYPOINT = 'waypoint';

    /** @var array|false */
    private $_waypoints;

    /* @var MapCreateMap[]|\Illuminate\Database\Eloquent\Builder[]|Collection */
    private $_data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \Exception
     */
    public function handle()
    {

        $this
            -> setData()
            -> makeWayPoints ( $this->_data )
        ;

        $this  -> constructAndCreateMap()
               -> deleteImages()
        ;

    }

    private function constructAndCreateMap(){

        MapCreateFactory::getRoute( env('HERE_API_KEY'),  new MapStore(

            $this->_data, // MapCreate Data + WayPoints collection combined
            MapImagery::LINE_THICKNESS,
            MapImagery::PEDESTRIAN_DAY,
            MapImagery::DEFAULT_STYLE,
            MapExt::JPEG,
            MapImagery::HI_RESS_PPI

        ) );

        return $this;
    }

    /**
     * Create a waypoint collection and append to data collection
     * @param Collection $data
     */
    private function makeWayPoints( Collection $data ){

        $data->map(function ($data) {

            // Get the starting point of the screen
            $start_waypoint = trim( $data->Screen->latitude . ',' .$data->Screen->longitude ) ;

            // Add Midway points if necessary and explode by ;
            $mid_waypoint = $data->mid_waypoints ? explode(';',$data->mid_waypoints ) : null;
            $mid_waypointSize =  $mid_waypoint !== null ? sizeof($mid_waypoint) : 0;

            // Append keys starting from N1 to size of n - 1
            $mid_waypointKeys = $this->addWaypointsKeys( $mid_waypoint, $mid_waypointSize,1 );

            // Create end waypoint by incrementing the size of midwaypoints
            $end_waypointName = self::WAYPOINT . ++$mid_waypointSize;

            // Append the end lat and lon
            $end_waypoint = $data->end_lat_waypoint . ',' . $data->end_lon_waypoint;

            // create the array structure
            $mid_waypointKeys +=  [

                'waypoint0' => $start_waypoint,
                $end_waypointName => $end_waypoint

            ];

            // append to data waypoints
            $data['waypoints'] = $mid_waypointKeys;

        });

    }

    /**
     * Create the waypoint keys for midwaypoints
     * @param $waypoints
     * @param null $waypointSize
     * @param int $startPoint
     * @return array|false
     */
    private function addWaypointsKeys(  $waypoints, $waypointSize = null, $startPoint = 0 ){

        if ( $waypoints === null )  return [];   // return an empty array if theres no midpoints

        // If Size is not passed through, then calculate it
        elseif( $waypointSize == null ) $waypointsSize = sizeof( $waypoints );

        // Using the waypoints array, assign a iteratable waypoint key id to each array element.
        $keys = [];
        for($i = 0 ; $i < $waypointSize; $i++){

            $keys[] = self::WAYPOINT . $startPoint++;

        }

        // Combine and return
        return array_combine($keys, $waypoints);
    }

    /**
     * Fetch the data using model relations
     * @return $this
     */
    private function setData()
    {
        $this->_data =  MapCreateMap::with('Screen:id,name,frame_id,latitude,longitude')
            ->with('MapCreateMapImagery')
            ->get();

        return $this;
    }


    /**
     * Delete the directory
     */
    private function deleteImages(){

        Storage::deleteDirectory( MapStore::DIR_NAME );

    }
}
