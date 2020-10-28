<?php


namespace Sheel\Map\Factories;

use Sheel\Map\MapCreate\CalculateRoute;
use Sheel\Map\MapStore;

abstract class MapCreateFactory
{

    public static function getRoute($apiKey, MapStore $client ) {

        // Create instance of object
      $CalcRoute =   new CalculateRoute($apiKey, $client);
      return $CalcRoute->handle();  // Calculate the route with the given injected mapstore object

    }

}
