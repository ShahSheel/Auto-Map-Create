<?php

namespace Sheel\Map\Models;

use Sheel\Map\Events\MapCreaterMapImageryDeletedEvent;
use Sheel\Map\Events\MapCreateMapImageryRestoredEvent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\hasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class MapCreateMapImagery
 * @package App
 * @property hasOne MapMapOption
 * @method static Builder withTrashed()

 */
class MapCreateMapImagery extends Model
{
    use SoftDeletes;

    protected $table = 'Map_map_imagery';

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [

        'deleted'   => MapCreaterMapImageryDeletedEvent::class,
        'restored'  => MapCreateMapImageryRestoredEvent::class,

    ];

    protected $fillable = [

        'map_option_id',
        'name',
        'map_width',
        'map_height',
        'line_color',
        'line_border_color',

    ];


    protected $casts = [

        'walkspeed' => 'float'

    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function MapMap(){

      return  $this->hasMany('Sheel\Map\Models\MapCreateMap','map_imagery_id','id');

    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function MapMapWithTrashed(){

        return  $this->MapMap()->withTrashed();

    }

    protected static function boot() {
        parent::boot();

        static::deleting(function($result) {
            $result->MapMap()->delete();
        });
    }

}
