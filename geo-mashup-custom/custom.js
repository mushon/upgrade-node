/**
 * GeoMashup customization examples 
 * 
 * * The filename must be changed to custom.js for customizations to take effect.
 * * You can edit the examples and enable customizations you want.
 * * If you know javascript, you can add your own customizations using the Google Maps API
 *   documented at http://code.google.com/apis/maps/documentation/reference.html#GMap2
 *
 * Properties
 * A properties object is available in all custom functions, and has these useful
 * variables:
 * * properties.url_path - the URL of the geo-mashup plugin directory
 * * properties.custom_url_path - the URL of the geo-mashup-custom plugin directory
 * * properties.template_url_path - the URL of the active theme directory
 * * properties.map_content - 'global', 'single', or 'contextual'
 * * properties.map_cat     - the category ID of a cateogory map
 *
 * Actions
 * Customization is achieved by adding "actions" to Geo Mashup. Currently, these actions
 * are available:
 *
 * newMap, clusterOptions, singleMarkerOptions, objectMarkerOptions, marker, categoryIcon, multipleIcon, 
 * categoryLine, loadedMap
 */

/**
 * Customize a Geo Mashup map before it is configured or loaded with markers. 
 *
 * @param object properties the properties of the GeoMashup being customized
 * @param google.maps.Map2 map        the Google Map object documented at http://code.google.com/apis/maps/documentation/reference.html#GMap2
 */
/* DELETE this line to enable this example
GeoMashup.addAction( 'newMap', function( properties, map ) {
	var image_div;

	// Add a loading icon
	image_div = document.createElement( 'div' );
	image_div.innerHTML = '<div id="my-loading-icon" style="-moz-user-select: none; z-index: 0; position: absolute; left: 200px; top: 200px;">' +
			'<img style="border: 0px none ; margin: 0px; padding: 0px; width: 16px; height: 16px; -moz-user-select: none; cursor: pointer;" src="' +
			properties.url_path + '/images/busy_icon.gif"/></a></div>';
	map.getContainer().appendChild( image_div );
	google.maps.Event.addListener( 'tilesloaded', function() {
		var image_div;
		image_div = document.getElementById( 'my-loading-icon' );
		if ( image_div ) {
			image_div.parentNode.removeChild( image_div );
		}
	} );
} );
DELETE this line to enable this example */

/**
 * Customize a Geo Mashup map after it is configured and the initial loading is done.
 *
 * @param object properties the properties of the GeoMashup being customized
 * @param google.maps.Map2 map        the Google Map object documented at http://code.google.com/apis/maps/documentation/reference.html#GMap2
 */
/* DELETE this line to enable this example
GeoMashup.addAction( 'loadedMap', function ( properties, map ) {
	var kml;

	// Load some KML only into global maps - for instance pictures of squirrels
	
	if (properties.map_content == 'global') {
		kml = new google.maps.GeoXml("http://api.flickr.com/services/feeds/geo/?g=52241987644@N01&lang=en-us&format=rss_200");
		map.addOverlay(kml);
	}

	// Recenter the map when displaying category ID 7
	if (properties.map_cat == 22) {
		map.setCenter( new google.maps.LatLng(18.5,15.3), 3 );
	}
} );
DELETE this line to enable this example */


// Use icon.png to replace single default marker
GeoMashup.addAction( 'singleMarkerOptions', function ( properties, options ) {
  
  // [not working] options.icon.image = properties.template_url_path + '/styles/images/icon.png';
  options.icon.image = properties.custom_url_path + '/icon.png';
  options.icon.iconSize = new google.maps.Size(22, 64);
  options.icon.iconAnchor = new google.maps.Point(11, 54);
  options.icon.infoWindowAnchor = new google.maps.Point(11, 54);
  
} );


// Use icon.png to replace default marker
GeoMashup.addAction( 'objectMarkerOptions', function( properties, options, object ) {
  
  options.icon.image = properties.custom_url_path + '/icon.png';
  options.icon.iconSize = new google.maps.Size(22, 64);
  options.icon.iconAnchor = new google.maps.Point(11, 54);
  options.icon.infoWindowAnchor = new google.maps.Point(11, 54);

} );


// Customize the marker icon for locations with multiple items.  The marker icon 
// already exists when we discover more items there, so we just change the image. 
GeoMashup.addAction( 'multiObjectMarker', function ( properties, marker ) {
	// Use icon.png for markers with multiple items.
	marker.setImage( properties.custom_url_path + '/icon.png' );
} );


// Overlay Upgrade Feeds to show on map
function customizeGeoMashupMap(mashup) {

  GeoMashup.map.addOverlay(new GGeoXml( 'http://wowm.org/uz/feed' ));
  GeoMashup.map.addOverlay(new GGeoXml( 'http://turbulence.org/upgrade_boston/feed' ));
  GeoMashup.map.addOverlay(new GGeoXml( 'http://www.upgrade-berlin.net/feed' ));
  GeoMashup.map.addOverlay(new GGeoXml( 'http://upgradechicago.org/feed' ));
  GeoMashup.map.addOverlay(new GGeoXml( 'http://digiwaukee.net/upgrade/feed/' ));
  GeoMashup.map.addOverlay(new GGeoXml( 'http://www.upgradesaopaulo.com.br/arte-novas-midias/feed/' ));
  GeoMashup.map.addOverlay(new GGeoXml( 'http://www.upgradejoburg.net/feed/' ));
  GeoMashup.map.addOverlay(new GGeoXml( 'http://www.upgrade.artapsu.com/?feed=rss2' ));
  // Quick fix to properly center map, must find a way to center to lastest post.
  GeoMashup.map.setCenter( new GLatLng(40.080313,-47.636719), 3 );
  
}

// Remove maximize info window option
GeoMashup.addAction( 'markerInfoWindowOptions', function( properties, location, options ) {
  options.maxContent = null;
} );

/*
function DelMark(marker)
  {
    if(marker) {
      GeoMashup.map.removeOverlay(marker);
    }
  } 
*/