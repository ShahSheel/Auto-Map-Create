<?php

namespace Sheel\Map\Models;

use Sheel\Map\Events\MapCreateMapDeletedEvent;
use Sheel\Map\Events\MapCreateMapRestoredEvent;
use Sheel\Map\Events\MapDeletedEvent;
use Sheel\Map\Events\MapRestoredEvent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * Class MapMapOptions
 * @package App
 *
 * @property Screen Screen
 * @property Campaign Campaign
 * @property string screen_orientation
 * @property string journey_type
 * @property string mode
 * @property string mid_waypoints
 * @property string end_lat_waypoint
 * @property string end_lon_waypoint
 * @property float walkspeed
 * @property int map_option_id
 * @property int map_results_id
 * @property int enable_traffic
 * @property int enable_auto_rotation
 * @property int enable_pin_adding
 * @property int enable_map_create
 * @method static Builder withTrashed()

 */
class MapCreateMap extends Model
{
    use SoftDeletes;

    protected $table = 'Map_map';

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [

        'deleted'   => MapCreateMapDeletedEvent::class,
        'restored'  => MapCreateMapRestoredEvent::class,

    ];

    protected $fillable = [

        'name',
        'campaign_id',
        'screen_id',
        'map_imagery_id',
        'screen_orientation',
        'mid_waypoints',
        'end_lat_waypoint',
        'end_lon_waypoint',
        'journey_type',
        'mode',
        'walkspeed',
        'override_rotation',
        'enable_traffic',
        'enable_auto_rotation',
        'enable_map_create',
        'map_result_id'

    ];


    protected $casts = [

        'enable_auto_rotation' => 'boolean',
        'enable_map_create' => 'boolean',

    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function Campaign() {

        return $this->hasOne( 'App\Campaign', 'id', 'campaign_id' );

    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function Screen(){

        return $this->hasOne('App\Screen', 'id','screen_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function MapMapImagery(){

     return  $this->hasOne('Sheel\Map\Models\MapCreateMapImagery', 'id','map_imagery_id');

    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function MapMapResults(){

     return $this->hasOne('Sheel\Map\Models\MapCreateMapResults', 'id','map_result_id');

    }

    /**
     * @return HasOne
     */
    public function MapMapImageryWithTrashed() {

        return  $this->MapMapImagery()->withTrashed();

    }

    /**
     * @return HasOne
     */
    public function MapMapResultsWithTrashed() {

        return$this->MapMapResults()->withTrashed();

    }


}
