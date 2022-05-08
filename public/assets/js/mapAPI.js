// OpenLayers API
// var map = new ol.Map({
//     target: 'map',
//     layers: [
//         new ol.layer.Tile({
//             source: new ol.source.OSM()
//         })
//     ],
//     view: new ol.View({
//         center: ol.proj.fromLonLat([37.41, 8.82]),
//         zoom: 4
//     })
// });

// set up containers for the map  + panel
var mapContainer = document.getElementById('mapContainer'),
    routeInstructionsContainer = document.getElementById('panel');

//Step 1: initialize communication with the platform
// In your own code, replace variable window.apikey with your own apikey
var platform = new H.service.Platform({
    'apikey': 'kfdp5s-hoIKBlYqz8wpSj7RkbelddDnMYl-e3uEwSHc'
});

var defaultLayers = platform.createDefaultLayers();

//Step 2: initialize a map - this map is centered over Berlin
var map = new H.Map(mapContainer,
    defaultLayers.vector.normal.map,{
        center: {lat: 34.32, lng: 108.55},
        zoom: 4,
        pixelRatio: (window.devicePixelRatio && window.devicePixelRatio > 1) ? 2 : 1
    });
// add a resize listener to make sure that the map occupies the whole container
window.addEventListener('resize', function () {
    map.getViewPort().resize();
});

//Step 3: make the map interactive
// MapEvents enables the event system
// Behavior implements default interactions for pan/zoom (also on mobile touch environments)
var behavior = new H.mapevents.Behavior(new H.mapevents.MapEvents(map));

// Create the default UI components
var ui = H.ui.UI.createDefault(map, defaultLayers, 'zh-CN');

