<?php


namespace Sheel\Map\Facade;


use Illuminate\Support\Facades\Facade;

class MapCreate extends Facade
{
    protected static function getFacadeAccessor()
    {
        // Facade name
        return 'MapCreate';
    }
}
