<?php


namespace Sheel\Map;
use Carbon\Carbon;
use Exception;
use Sheel\Map\MapDetails\MapImagery;
use Illuminate\Database\Eloquent\Collection;
use InvalidArgumentException;

/**
 * Class Client
 * @package Sheel\MapCreate\Factories
 *
 */
class MapStore
{

    CONST AUTOMATIC_ROTATION = 'automatic';

    CONST DIR_NAME = 'map-images';


    /** @var array $screens @property screen_frame_id */
    protected $screens;

    /** @var array $waypoints */
    protected $waypoints;

    /** @var string mode */
    protected $mode;

    /** @var string $journeyType */
    protected $journeyType;

    /** @var String */
    protected $legattributes = 'li';

    /** @var int $walkSpeed */
    protected $walkSpeed;

    /** @var int $mapWidth */
    protected $mapWidth;

    /** @var int $mapHeight */
    protected $mapHeight;

    /** @var string $lineColor */
    protected $lineColor;

    /** @var int|string $rotationDegrees */
    protected $rotationDegrees;

    /** @var bool $noCrop */
    protected $noCrop;

    /** @var bool $noCopy */
    protected $noCopy;

    /** @var bool $noCompass */
    protected $noCompass;

    /** @var int $mapType */
    protected $mapType;

    /** @var String $mapStyle */
    protected $mapStyle;

    /** @var int $lineThickness */
    private $lineThickness;

    /* @var int $extension */
    private $extension;

    private $_ppi;

    /** @var in $campaignID */
    private $campaignID;

    /** @var string $traffic */
    private $traffic;

    /** @var int $overrideRotation */
    private $overrideRotation;
    /** @var  */
    private $panelRoute;
    /**
     * MapStore constructor.
     * @param panelroute
     * @param int $campaignID
     * @param array $Screen
     * @param array $waypoints
     * @param string $mode
     * @param string $journeyType
     * @param int $walkSpeed
     * @param int $pixelMapWidth
     * @param int $pixelMapHeight
     * @param string $lineColor
     * @param int $lineThickness
     * @param $rotationDegrees
     * @param int $mapType
     * @param string $mapStyle
     * @param int $extension
     * @param int $ppi
     * @param bool $noCrop
     * @param bool $noCopy
     * @param bool $noCompass
     * @throws Exception
     */
    public function __construct(

        $PanelRoute,
      //  array $waypoints,
     //   array $Screen,
        $lineThickness,
        int $mapType,
        $mapStyle = 'default',
        $extension = 1,
        $ppi = 72,
        $noCrop = true,
        $noCopy = true,
        $noCompass = true

    )
    {

        $this->setPanelRoute($PanelRoute);

        $this->setLineOptions( $lineThickness );
        $this->setMapOptions(  $mapType, $mapStyle, $extension, $ppi );
        $this->setMisc( $noCrop, $noCompass, $noCopy );

    }


    /**
     * @param $pixelMapHeight
     * @param $pixelMapWidth
     * @param $mapType
     * @param $mapStyle
     * @param $rotationDegrees
     * @param $extension
     * @param $ppi
     * @throws Exception
     */
    private function setMapOptions ( $mapType, $mapStyle,$extension, $ppi ){

        $this->setMapType( $mapType );
        $this->setMapStyle( $mapStyle );
        $this->setExtension( $extension );
        $this->setPPI ( $ppi, $mapType );


    }

    /**
     * @param $lineColor
     * @param $lineThickness
     */
    private function setLineOptions( $lineThickness ){

        $this->setLineThickness( $lineThickness );

    }

    /**
     * @param $noCrop
     * @param $noCompass
     * @param $noCopy
     */
    private function setMisc( $noCrop, $noCompass, $noCopy ) {

        $this->setNoCrop( $noCrop );
        $this->setNoCompass( $noCompass );
        $this->setNoCopy( $noCopy );

    }

    /**
     * @return String
     */
    public function getLegattributes(): String
    {
        return $this->legattributes;
    }


    /**
     * @return bool
     */
    public function isNoCrop(): bool
    {
        return (bool) $this->noCrop;
    }

    /**
     * @return bool
     */
    public function isNoCopy(): bool
    {
        return (bool) $this->noCopy;
    }

    /**
     * @return bool
     */
    public function isNoCompass(): bool
    {
        return  $this->noCompass;
    }

    /**
     * @return int
     */
    public function getMapType(): int
    {
        return $this->mapType;
    }


    /**
     * @param string $lineColor
     * @return MapStore
     */
    public function setLineColor(string $lineColor): MapStore
    {
        $this->lineColor = $lineColor;
        return $this;
    }

    /**
     * @return int
     */
    public function getLineThickness(): int
    {
        return $this->lineThickness;
    }

    /**
     * @param int $lineThickness
     * @return MapStore
     */
    public function setLineThickness(int $lineThickness): MapStore
    {
        $this->lineThickness = $lineThickness;
        return $this;
    }


    /**
     * @param bool $noCrop
     * @return MapStore
     */
    public function setNoCrop(bool $noCrop): MapStore
    {
        $this->noCrop = $noCrop;
        return $this;
    }

    /**
     * @param bool $noCopy
     * @return MapStore
     */
    public function setNoCopy(bool $noCopy): MapStore
    {
        $this->noCopy = $noCopy;
        return $this;
    }

    /**
     * @param bool $noCompass
     * @return MapStore
     */
    public function setNoCompass(bool $noCompass): MapStore
    {
        $this->noCompass = $noCompass;
        return $this;
    }

    /**
     * @param int $mapType
     * @return MapStore
     */
    public function setMapType(int $mapType): MapStore
    {
        $this->mapType = $mapType;
        return $this;
    }

    /**
     * @return String
     */
    public function getMapStyle(): String
    {
        return $this->mapStyle;
    }

    /**
     * @param String $mapStyle
     * @return MapStore
     */
    public function setMapStyle(String $mapStyle): MapStore
    {
        $this->mapStyle = $mapStyle;
        return $this;
    }


    /**
     * @return int
     */
    public function getExtension(): int
    {
        return $this->extension;
    }


    /**
     * @param int $extension
     * @return MapStore
     */
    public function setExtension(int $extension): MapStore
    {
        $this->extension = $extension;
        return $this;
    }

    /**
     * @param $ppi
     * @param $mapStyle
     * @throws Exception
     */
    public function setPPI ( $ppi, $mapStyle ){

        // Get Constant Name
        $constantName =  MapImagery::toString( $mapStyle, MapImagery::getClassName() );
        // If the mobile ppi is not assigned to mobile map type
        if ( strpos( $constantName, 'mobile' ) && $ppi !== MapImagery::MOBILE_PPI ) {

            // Throw error
            throw new Exception('MobilePPI needs to be supplied the const MOBILE_PPI!');

        }
        else{
            // Set
            $this->_ppi = $ppi;

        }

    }

    /**
     * @return mixed
     */
    public function getPPI()
    {
        return $this->_ppi;
    }

    /**
     * @return string|int
     */
    public function getRotationDegrees()
    {
        return $this->rotationDegrees;
    }

    /**
     * @param string|int $rotationDegrees
     */
    public function setRotationDegrees($rotationDegrees)
    {
        $this->rotationDegrees = $rotationDegrees;
    }

    /**
     * @return mixed
     */
    public function getPanelRoute()
    {
        return $this->panelRoute;
    }

    /**
     * @param mixed $panelRoute
     */
    public function setPanelRoute($panelRoute)
    {
        $this->panelRoute = $panelRoute;
    }

}
