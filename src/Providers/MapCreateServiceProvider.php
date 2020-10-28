<?php

namespace Sheel\Map\Providers;

use Sheel\Map\Facade\MapCreate;
use Sheel\Map\Factories\MapCreateFactory;
use Sheel\Map\MapOptions\CalculateRoute;
use Sheel\Map\MapStore;
use Illuminate\Support\ServiceProvider;
class MapCreateServiceProvider extends ServiceProvider {

    public function boot()
    {
        // Define where we'll load the migrations from.
        $this->loadMigrationsFrom( __DIR__ . '/../Migrations' );
    }

    public function register()
    {

        // We cant register this as MapStore relies on user dependencies 


        // $this->app->bind('MapCreate', function (MapStore $client ) {
        //     return MapCreateFactory::getRoute( env( 'HERE_DIRECTIONS_API_KEY'), $client );
        // });

        


    }
}
