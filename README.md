## What does this project do?
---

* Generates a dynamic map when giving the screen latitude, orientation and end latitude 
* Populates the table `MapCreate_map_results` with the distance (m) and average time (seconds) to take from screen to end latitudes

When dispatching the command, it will create the assets for you. If you need to view the raw generated png, then go uncomment `DeleteImages()` in `Jobs\GenerateStaticMap`. In addition to this, if you want to change the directory name, specify the name in `MapStore::DIR_NAME`, this is currently `map-images`. 

## Notes 
---
* Spreadsheet importer is required is required to obtain neccessary data for it to create maps for. 
* To add midway points in the spreadsheet importer, add the latitudes and longitudes in a format of:
`123,456;7891;2345`. This will detect `123,456` as `Midway Point A` and `7891,2345` as `Midway Point B`. The delimiter is `;` 

## Usage 
---

### EventsServiceProvider
In `app/Providers/EventsServiceProvider.php` add:

```javascript
  'Sheel\Map\Events\MapMapDeletedEvent' => [
            'Sheel\Map\Listeners\MapMapDeletedListener'
        ],

        'Sheel\Map\Events\MapMapRestoredEvent' => [
            'Sheel\Map\Listeners\MapMapRestoredListener'
        ],

        'Sheel\Map\Events\MapMapImageryDeletedEvent' => [
            'Sheel\Map\Listeners\MapMapImageryDeletedListener'
        ],

        'Sheel\Map\Events\MapMapImageryRestoredEvent' => [
            'Sheel\Map\Listeners\MapMapImageryRestoredListener'
        ]

```
```
```

### Kernel
---
Add command class `DispatchMap::class` to the `commands array`

### Run
---

Run using the command: `art Map:map`

---

## Under the hood

#### Customise every map produced
---

Currently everything is set to default, you can override the map returned by changing the constants to which ones you need be. For mobile map imagery, it must use `Mobile_PPI` otherwise it will return an error instructing you to change it!


```php
$MapObject = new MapStore(
   $this->_data, // Screen data + WayPoints collection added 
   MapImagery::LINE_THICKNESS,
   MapImagery::PEDESTRIAN_DAY,
   MapImagery::DEFAULT_STYLE,
   MapExt::JPEG,
   MapImagery::HI_RESS_PPI
 );
```

  

#### Map Imagery Constants
---

Use these constants when creating a map. Currently uses pedestrian view
 
```javascript
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
```

### Map Extension Type
---

Use `MapExt` and use the following constant for the type of extension you wish. 
```javascript
    /** @var int Format Type */
    CONST PNG = 0;
    CONST JPEG = 1;
    CONST GIF = 2;
    CONST BMP = 3;
    CONST PNG8 = 4;
    CONST SVG = 5;
```
