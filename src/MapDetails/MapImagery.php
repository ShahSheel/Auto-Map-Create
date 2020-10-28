<?php


namespace Sheel\Map\MapDetails;


class MapImagery extends AbstractMap
{

    /** Map Type  */

    CONST NORMAL_DAY = 0; // Normal map view in day light mode.
    CONST SATELLITE_DAY = 1; // Satellite map view in day light mode.
    CONST TERRAIN_DAY = 2; // Terrain map view in day light mode.
    CONST HYBRID_DAY = 3; // Satellite map view with streets in day light mode.
    CONST NORMAL_DAY_TRANSIT = 4; // Normal grey map view with public transit in day light mode.
    CONST NORMAL_DAY_GREY = 5; // Normal grey map view in day light mode (used for background maps).
    CONST NORMAL_DAY_MOBILE = 6;  // Normal map view for small screen devices in day light mode.
    CONST NORMAL_NIGHT_MOBILE = 7;  // Normal map view for small screen devices in night mode.
    CONST TERRAIN_DAY_MOBILE = 8; // Terrain map view for small screen devices in day light mode.
    CONST HYBRID_DAY_MOBILE = 9; // Satellite map view with streets for small screen devices in day light mode.
    CONST NORMAL_DAY_TRANSIT_MOBILE = 10; // Normal grey map view with public transit for small screen devices in day light mode.
    CONST NORMAL_DAY_GREY_MOBILE = 11; // Normal grey map view in day light mode.
    CONST CARNAV_DAY_GREY = 12;   // Map view designed for navigation devices.
    CONST PEDESTRIAN_DAY = 13;  // Map view designed for pedestrians walking by day.
    CONST PEDESTRIAN_NIGHT = 14;  // Map view designed for pedestrians walking by night

    /** Resolution  */
    CONST DEFAULT_PPI = 72;
    CONST MOBILE_PPI = 250;   // Only mobile can use this ppi
    CONST HI_RESS_PPI = 320;
    CONST SUPER_HI_RESS = 500;

    CONST LINE_THICKNESS  = 4; // Default Line Thickness

    /** Map Style */
    CONST ALPS_STYLE = 'alps';
    CONST FLEET_STYLE = 'fleet';
    CONST MINI_STYLE = 'mini';
    CONST DEFAULT_STYLE = 'default';

}
