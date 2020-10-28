<?php

namespace Sheel\Map\MapCreate;

use Sheel\Map\MapStore;
use Sheel\Map\Factories\MapCreateFactory;


/**
 * Class CalculateRoute
 * @package Sheel\MapCreate\Map
 */

class CalculateRoute extends MapCreateFactory{

    /** @var string $_apiKey  */
    protected $apiKey;

    /* @var MapStore $mapStore */
    protected $mapStore;

    /** @var string $_uri */
    private $_uri;

    /** @var string $_uriSegment */
    private $_uriSegment = "https://route.ls.hereapi.com/routing/7.2/calculateroute.json?";
    /** @var string $_stringWaypoints */
    private $_toStringWaypoints;

    private $_feed;

    /* @var string $_coordinates*/
    protected $_coordinates;

    /** @var float $_distance_m */
    private $_distance_m;

    /** @var float $_travel_time_s*/
    private $_travel_time_s;

    /** @var array $_parentJourneyInfo  */
    protected $_parentJourneyInfo;

    /** @var array $coordsArray */
    private $_coordsArray = array();

    /** @var float $_traffic_time_s */
    private $_traffic_time_s;

    /** @var string $_trafficCondition */
    private $_trafficCondition;

    /**
     * Set field variables with api key and injected mapstore object
     * @param $apiKey
     * @param MapStore $mapStore
     */
    public function __construct( $apiKey, MapStore $mapStore ){

        $this->apiKey = $apiKey;
        $this->mapStore = $mapStore;
    }


    /**
     * @throws \Exception
     */
    public function handle ( ){

        $this
            -> forEachScreen()
        ;

    }

    private function forEachScreen(){

        $count = 0;
        foreach ( $this->mapStore->getPanelRoute() as $screen ){

                $this
                    ->toStringWaypoints( $screen ) // Convert arraylist of waypoints to a string
                    ->isTraffic( $screen ) // construct a traffic enabled / disabled per screen
                    ->constructURI( $screen ) // construct the uri request
                    ->fetchContents( $screen ) // get contents
                    ->decodeFeed() // decode feed
                    ->getFeed() // Check if feed is not null
                    ->getRouteShape( $screen ) // Get all the coordinates to make the route shape
                    ->generateMap( $screen ) // Make the map
                ;
            $count++;

        }
    }


    /**
     * Obtain all they keys and values and make a string so it can be loaded as a uri
     * @return $this
     */
    private function toStringWaypoints( $screen ){

        $this->_toStringWaypoints = urldecode( http_build_query( $screen->waypoints) );

        return $this;

    }

    /**
     * Obtain all they keys and values and make a string so it can be loaded as a uri
     * @return $this
     */
    private function isTraffic( $screen ){

        $this->_trafficCondition = $screen->enable_traffic ? 'traffic:enabled'  : 'traffic:disabled';
        printf("\n---------------------- Screen ID: " . $screen->Screen->frame_id . "-------------------------------" );

        printf("\n Traffic mode: ". $this->_trafficCondition );


        return $this;

    }


    /**
     * @return $this
     */
    private function constructURI( $screen ){

        $this->_uri = $this->_uriSegment .
            'apiKey='.$this->apiKey . '&' . // api key
            'mode=' . $screen->mode .';' . $screen->journey_type . ';' . $this->_trafficCondition . '&' . // Mode type
            'legattributes=' . $this->mapStore->getLegattributes() . '&' . // Legattributes
            'walkSpeed=' . $screen->walkspeed . '&' .// walkspeed
            $this->_toStringWaypoints  // Waypoints array converted to a string
        ;

        
        return $this;

    }

    /**
     * @return $this
     */
    protected function fetchContents( $screen ) {

        printf("\n Fetching Contents ");
        // Fetch the contents
        $this->_feed = file_get_contents($this->_uri);

        return $this;

    }

    /**
     * @return $this
     */
    protected function decodeFeed() {

        $this->_feed = json_decode( $this->_feed );

        return $this;

    }

    /**
     * @return $this
     * @throws \Exception
     */
    protected function getFeed(){

        // If feed has never been set
        if( is_null( $this->_feed ) ) {

            throw new \Exception( 'There is no feed as none of the fetch methods have been called on URI:' .  $this->_uri, 400 );

        }

        return $this;

    }

    /**
     * Sets coordinates list as a String from A to C (including B, B1, B2 waypoints)
     * @return $this
     */
    private function getRouteShape( $screen ) {
        printf("\n Producing Route Shape from Screen to destination" );

        $this->_coordinates = null;  // redeclare coordnates as null

        foreach ( $this->_feed->response->route as $item  ) {   // runs once

            $this->getSummary( $item )->populatedb( $screen );

            foreach ($item->leg as $maneuver) {   // runs once

                foreach ($maneuver->link as $link) { // multpile times

                    foreach ($link->shape as $shape => $value) {   // multiple times

                        // Convert from array to string values, so we can use it as a uri snippet
                        $latitude = $value;
                        $value = explode(",", $latitude); // explode by ,
                        $latitude = $value[0];
                        $longitude = $value[1];


                        $this->_coordinates  .= (string) $latitude . ',' . $longitude . ",";

                    }

                }
            }
        }

        $coords = str_replace('"', "", $this->_coordinates); // Remove all speech marks

        $this->_coordinates = substr( ( String ) $coords,0, -1); // Cast to String, remove the comma at the end

        return $this;
    }

    /**
     * Gets the distance summary
     * @param $route
     */
    private function getSummary( $route ){

        $this->_distance_m = $route->summary->distance;  //distance
        $this->_travel_time_s = $route->summary->baseTime; //travel time in seconds
        $this->_traffic_time_s = isset( $route->summary->trafficTime ) ? $route->summary->trafficTime : null; // traffic itme in seconds if exists

        return $this;
    }


    private function populateDb( $screen ){

      // create an array, so that we can update the database for each screen

     $this->_parentJourneyInfo = [

            'map_options_id' => $screen->id,
            'map_imagery_id' => $screen->MapMapImagery->id,
            'distance' => $this->_distance_m,
            'baseTime' => $this->_travel_time_s,
            'trafficTime' => $this->_traffic_time_s,
            'map_asset_id' => null

     ];

        return $this; 
    }

    
    /**
     * Generate the map
     */
    private  function generateMap( $screen ){

        $StaticMap = new StaticMapGenerator( $this->apiKey, $screen, $this->mapStore, $this->_coordinates, $this->_parentJourneyInfo );
        $StaticMap->createMap();
    }

}
