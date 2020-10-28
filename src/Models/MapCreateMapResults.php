<?php

namespace Sheel\Map\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MapCreateMapResults extends Model
{
    use SoftDeletes;

    protected $table = 'Map_map_results';

    protected $fillable = [

        'distance',
        'baseTime',
        'trafficTime',
        'map_options_id',
        'map_imagery_id',
        'map_asset_id'

    ];


    protected $casts = [

        'distance' => 'float',
        'baseTime' => 'float',
        'trafficTime' => 'float'

    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function MapMap(){

      return  $this->hasOne('Sheel\Map\Models\MapCreateMap', 'map_result_id','id');

    }

    protected static function boot() {
        parent::boot();

        static::deleting(function($result) {
            $result->MapMap()->delete();
        });
    }
}
