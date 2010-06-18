CakePHP Yahoo Geo Planet Plugin
===============================

A CakePHP plugin for interacting with the <a href="http://developer.yahoo.com/geo/geoplanet/">Yahoo Geo Planet API</a>.

Provides a simple, familiar API for things like searching for places matching a given search term. Can be used to power 'store finder' or 'branch locator' type functionality.

The plugin contains controllers and views but they are really only included to demonstrate the plugin working. The interesting stuff is in the models and datasource. How you should use it therefore is by accessing the model methods directly from classes in your own application, see below for examples.

Dependencies
------------

  - <a href="http://github.com/neilcrookes/CakePHP-ReST-DataSource-Plugin">CakePHP Rest DataSource Plugin</a>

Installation
------------

  - Get the <a href="http://github.com/neilcrookes/CakePHP-ReST-DataSource-Plugin">CakePHP Rest DataSource Plugin</a> and add to plugins/rest
  - Get this plugin and add it to plugins/yahoo_geo_planet
  - Get a Yahoo Developer <a href="http://developer.yahoo.com/wsregapp/">Application Id</a>
  - Copy the $yahooGeoPlanet property from plugins/yahoo_geo_planet/config/yahoo_geo_planet_config.php.default to you app/config/database.php file and add in your application id
  - Test by pointing your browser to 'http://your-host-name/yahoo_geo_planet/yahoo_geo_planet_places/places' and enter a place name

Usage
-----

Include the YahooGeoPlanet.YahooGeoPlanetPlace model in you Controller::uses property or use ClassRegistry::init('YahooGeoPlanet.YahooGeoPlanetPlace');

  - Retrieving places matching a given search term:

        YahooGeoPlanetPlace::find('places', array(
          'conditions' => array(
            'q' => 'Southampton'
          ),
          'page' => 1,
          'limit' => 10,
        ));

  - Retrieving places matching a given search term of a given type:

        YahooGeoPlanetPlace::find('places', array(
          'conditions' => array(
            'q' => 'Southampton',
            'type' => 7
          ),
        ));

    <a href="http://developer.yahoo.com/geo/geoplanet/guide/concepts.html#placetypes">list of place types and numeric codes</a>

  - Retrieving places matching a given search term, prioritising places in the given focus area:

        YahooGeoPlanetPlace::find('places', array(
          'conditions' => array(
            'q' => 'Southampton',
            'focus' => 2488042
          ),
        ));

    Focus value should be an ISO-3166-1 country code or a WOEID

  - Retrieving places for the given woeids (MULTIPLE WOEIDS - note plural places)

        YahooGeoPlanetPlace::find('places', array(
          'conditions' => array(
            'woeid' => array(2488042,2488836,2486340)
          ),
        ));

  - Retrieving place for the given woeid (SINGLE WOEID - note singular place)

        YahooGeoPlanetPlace::find('place', array(
          'conditions' => array(
            'woeid' => 2488042
          ),
        ));

  - See the YahooGeoPlanetPlacesController::places() method for example of how to paginate a result set

Results
-------
Results from the API calls look like this:

        Array
            (
                [woeid] => 35356
                [placeTypeName] => Town
                [placeTypeName attrs] => Array
                    (
                        [code] => 7
                    )

                [name] => Southampton
                [country] => United Kingdom
                [country attrs] => Array
                    (
                        [type] => Country
                        [code] => GB
                    )

                [admin1] => England
                [admin1 attrs] => Array
                    (
                        [type] => Country
                        [code] => GB-ENG
                    )

                [admin2] => Hampshire
                [admin2 attrs] => Array
                    (
                        [type] => County
                        [code] => GB-HAM
                    )

                [admin3] =>
                [locality1] => Southampton
                [locality1 attrs] => Array
                    (
                        [type] => Town
                    )

                [locality2] =>
                [postal] =>
                [centroid] => Array
                    (
                        [latitude] => 50.909939
                        [longitude] => -1.40732
                    )

                [boundingBox] => Array
                    (
                        [southWest] => Array
                            (
                                [latitude] => 50.879452
                                [longitude] => -1.48125
                            )

                        [northEast] => Array
                            (
                                [latitude] => 50.973251
                                [longitude] => -1.31432
                            )

                    )

                [areaRank] => 5
                [popRank] => 11
                [uri] => http://where.yahooapis.com/v1/place/35356
                [lang] => en-US
            )


To do
-----

Implement all the calls available on the <a href="http://developer.yahoo.com/geo/geoplanet/guide/api-reference.html">Yahoo GeoPlanet API</a>