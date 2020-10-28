<?php

namespace Sheel\Map\MapCreate;

use App\Asset;
use Sheel\Map\Models\MapCreateMap;
use Sheel\Map\Models\MapCreateMapResults;
use Sheel\File\UploadMimicker;
use Sheel\Map\MapStore;
use Sheel\Map\MapDetails\MapExt;
use Sheel\Map\Models\screenMap;
use Illuminate\Support\Facades\Storage;

class StaticMapGenerator extends CalculateRoute {


    /** @var string $_uri */
    private $_uri = "https://image.maps.ls.hereapi.com/mia/1.6/turnpoint?";

    /** @var $_screen */
    private $_screen;

    /** @var int $_rotation */
    private $_rotation;

    /**
     * StaticMapGenerator constructor.
     * @param $apiKey
     * @param MapStore $mapStore
     * @param $coordinates
     */
    public function __construct($apiKey , $screen, MapStore $mapStore, $coordinates, $parentJourneyInfo )
    {
        $this->_coordinates = $coordinates;
        $this->_screen = $screen;
        $this->_parentJourneyInfo = $parentJourneyInfo;


        parent::__construct($apiKey, $mapStore);
    }


    /**
     *  Creates the map
     */
    public function createMap (){

        $this
            -> calculateRotation()
            -> constructURI()  // Construct URI
            -> createDirectory() // Create directory
            -> saveImageAndCreateAsset() // Save the image from the URI created  if 'create map' is allowed
        ;

    }

    private function calculateRotation(){

        printf("\n Determining rotation" );

        if (  $this->_screen->enable_auto_rotation ) {

           switch (strtolower(trim($this->_screen->screen_orientation))) {

               case 'n':
                   $this->mapStore->setRotationDegrees(0);
                   break;
               case 'ne':
                   $this->mapStore->setRotationDegrees(-45);
                   break;
               case 'e':
                   $this->mapStore->setRotationDegrees(-90);
                   break;
               case 'se':
                   $this->mapStore->setRotationDegrees(-45);
                   break;
               case 's':
                   $this->mapStore->setRotationDegrees(180);
                   break;
               case 'sw':
                   $this->mapStore->setRotationDegrees(45);
                   break;
               case 'w':
                   $this->mapStore->setRotationDegrees(90);
                   break;
               case 'nw':
                   $this->mapStore->setRotationDegrees(45);
                   break;

           }

       }
       else{

           $this->mapStore->setRotationDegrees( $this->_screen->override_rotation );

       }

       return $this;

    }

    /**
     * Construct the URI
     * @return $this
     */
    private function constructURI(){

        $this->_uri = $this->_uri .
           'apiKey='.$this->apiKey . '&' . // api key
           'w=' . $this->_screen->MapMapImagery->map_width . '&' . // Width of map
           'h=' . $this->_screen->MapMapImagery->map_height . '&' . // Height of map
           'ra=' . $this->mapStore->getRotationDegrees() . '&' . // Rotation applied
           'f=' . $this->mapStore->getExtension() . '&' . // Image type, png, jpeg ect
           't=' . $this->mapStore->getMapType() . '&' . // Pedestrian? Mobile? ..Map type
           'nocmp=' . ( $this->mapStore->isNoCompass() ? 'true' : 'false' ). '&' . // Compass display
           'nocrop=' . ( $this->mapStore->isNoCrop() ? 'true' : 'false' ) . '&' . // Crop to fit
           'nocp=' . ( $this->mapStore->isNoCopy() ? 'true' : 'false' ) . '&' . // Company logo display
           'ppi=' . $this->mapStore->getPPI() . '&' .  // resolution
           'lc=' . $this->_screen->Mapmapimagery->line_color . '&' . // Line colour
           'sc=' . $this->_screen->Mapmapimagery->line_border_color . '&' . // Line border color
           'lw=' . $this->mapStore->getLineThickness() . '&' .  // Line Width
           'style=' . $this->mapStore->getMapStyle() . '&' . // Style of map
           'r0=' . $this->_coordinates // coords list from parent

       ;

        return $this;

    }

    /**
     * Create directory in Storage/App
     * @return $this
     */
    private function createDirectory(){

     // Create directory if not created
      Storage::makeDirectory( MapStore::DIR_NAME );

      return $this;

    }

    /**
     * Save the image in the directory created if enable_map_create is enabled
     */
    private function saveImageAndCreateAsset(){


        if ( $this->_screen->enable_map_create ){

            printf("\n Asset Mode Enabled, Fetching Image from URI" );

            $name = $this->createFileName();
            $path = $this->getPath() .'/' . $name;

            file_put_contents( $path , file_get_contents( $this->_uri ) );

            $this->createAsset( $path, $name )->updateDatabase( $name );

        }

        else{

            printf("\n Asset Mode Disabled, skipping to next screen");

        }

    }

    /**
     * Create the directory - storage/app/DIR_NAME
     * @return string
     */
    private function getPath (){

        return storage_path('app/' . MapStore::DIR_NAME );
    }
    /**
     * Generate filename
     * @return string
     * @throws \ReflectionException
     */
    private function createFileName(){

        // Create extension by getting the chosen extension type
        $extension = '.' . MapExt::toString( $this->mapStore->getExtension(), MapExt::getClassName() );

        // Create a file name called Map_FrameID_Rotation.ext
        return 'map_' . $this->_screen->Screen->frame_id  . '_' . $this->_screen->screen_orientation . '_'. $this->mapStore->getRotationDegrees()  . $extension;

    }


    /**
     * Create Asset
     * @param $path
     * @param $name
     * @return $this
     */
    private function createAsset( $path, $name ){

        $mimicker = new UploadMimicker($path, $this->_screen->campaign_id , $name); // Construct using the information given from Map Object

        $mimicker->handle();

        return $this;

    }


    /**
     * Get Asset ID
     * @param $name
     * @return mixed
     */
    private function getAssetID( $name ){

      return  Asset::query()->where('name', $name )->value('id');

    }


    /**
     * Update the database for each screen
     * @param $name
     */
    private function updateDatabase( $name ){
        printf("\n Updating Database ");
        printf("\n-----------------------------------------------------------------------" );

        $this
            ->insertOrUpdateMap( $this->insertOrUpdateResults( $name ) )
        ;

    }

    /**
     * Insert or Update  Or Map Results
     * @param $name
     * @return mixed
     */
    private function insertOrUpdateResults( $name ){

        $this->_parentJourneyInfo['map_asset_id'] = $this->getAssetID( $name );

        $result = MapCreateMapResults::query()->updateOrCreate([
            // Update the record if contains the map_asset id, map_options id
            'map_options_id' => $this->_parentJourneyInfo['map_options_id'] ,
            'map_asset_id' => $this->getAssetID($name)
        ],$this->_parentJourneyInfo);


        return $result->id;

    }

    /**
     * Insert Result ID in MapCreate using the ID returned from Map Result
     * @param $id
     */
    private function insertOrUpdateMap( $id ){

        MapCreateMap::where('id', $this->_screen->id)
            ->update([
                'map_result_id' => $id
            ]);

    }



}
