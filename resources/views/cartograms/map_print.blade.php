<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>Картограмма для {{ $value }}</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 4.1.1 -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
    <!-- Ionicons -->
    <link href="//fonts.googleapis.com/css?family=Lato&display=swap" rel="stylesheet">
    <link href="{{ asset('assets/css/@fortawesome/fontawesome-free/css/all.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/css/iziToast.min.css') }}">
    <link href="{{ asset('assets/css/sweetalert.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
  
    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('web/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('web/css/components.css')}}">
    

    <link href="https://api.mapbox.com/mapbox-gl-js/v2.5.1/mapbox-gl.css" rel="stylesheet">

    <style>
        #map {
            width: 700px;
            height: 700px;
			background: blue;
        }

        .mapboxgl-popup {
            max-width: 200px;
        }

        .mapboxgl-popup-content {
            text-align: center;
            font-family: 'Open Sans', sans-serif;
        }
    </style>

    @foreach ($markerImgs as $id => $ms)
        @foreach ($ms as $v => $m)
            <style>
            .marker-{{ $id }}-{{ $v }} {
                background-image: url('{{ asset($m["path"]) }}');
                background-repeat: no-repeat;
                background-size: contain;
                width: 48px;
                height: 48px;
                border-radius: 50%;
                background-color: none;
                cursor: pointer;
            }
            </style>
        @endforeach
    @endforeach

    <style type="text/css">
        .legend {
            display: flex;
            width: 240px;
            margin-left: 20px;
        }

        .column-legend {
            height: 100px;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            margin-right: 5px;
        }
    </style>

</head>
<body>

<div id="app">

    <div class="card-body" style="padding: 0">
        <div id="map"></div>
    </div>
</div>

<script src="https://api.mapbox.com/mapbox-gl-js/v2.5.1/mapbox-gl.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@turf/turf@6/turf.min.js"></script>

<!-- VAR -->
<script>
    // console.log(geometry);
    var _polygon;

    let _value = '{{ $value }}';

    // grid
    const graticule = {
        type: 'FeatureCollection',
        features: []
    };
    const DIST_IN_KM = 0.05/111;

    // grid
    const GRID = {
        ON: "on",
        OFF: "off"
    };

    // subpolygons
    const MODE = {
        SUBPOLYGON_ON: "subpolygon_on",
        SUBPOLYGON_OFF: "subpolygon_off"
    };

    // init values
    var showGrid = GRID.OFF;
    var mode = MODE.SUBPOLYGON_OFF;
    // console.log(showGrid);

    var spMarkers = [];

    var geometry = {!! $cartogram->field->polygon->geometry !!};

    var fields = [];
    fields.push(geometry);


    // console.log(fields);

    var _bbox;
    var _points = [];
    _results = {
        'humus': [],
        'ph': [],
        'p': [],
        's': [],
        'k': [],
        'no3': [],
        'no3_2': [],
        
        'b': [],
        'fe': [],
        'cu': [],
        'zn': [],
        'mn': [],
        'na': [],
        'calcium': [],
        'magnesium': [],
        'salinity': [],
        'salinity_2': [],
        'absorbed_sum': [],

        'id': [],

    };

    // results
    @foreach ($cartogram->field->polygon->points as $point)
        _results['humus'].push({{ $point->sample->result->humus != null ? $point->sample->result->humus : 0 }});
        _results['ph'].push({{ $point->sample->result->ph != null ? $point->sample->result->ph : 0 }});
        _results['p'].push({{ $point->sample->result->p != null ? $point->sample->result->p : 0 }});
        _results['s'].push({{ $point->sample->result->s != null ? $point->sample->result->s : 0 }});
        _results['k'].push({{ $point->sample->result->k != null ? $point->sample->result->k : 0 }});
        _results['no3'].push({{ $point->sample->result->no3 != null ? $point->sample->result->no3 : 0 }});
         _results['no3_2'].push({{ $point->sample->result->no3 != null ? $point->sample->result->no3 : 0 }});


        _results['b'].push({{ $point->sample->result->b != null ? $point->sample->result->b : 0 }});
        _results['fe'].push({{ $point->sample->result->fe != null ? $point->sample->result->fe : 0 }});
        _results['cu'].push({{ $point->sample->result->cu != null ? $point->sample->result->cu : 0 }});
        _results['zn'].push({{ $point->sample->result->zn != null ? $point->sample->result->zn : 0 }});
        _results['mn'].push({{ $point->sample->result->mn != null ? $point->sample->result->mn : 0 }});
        _results['na'].push({{ $point->sample->result->na != null ? $point->sample->result->na : 0 }});
        _results['calcium'].push({{ $point->sample->result->calcium != null ? $point->sample->result->calcium : 0 }});
        _results['magnesium'].push({{ $point->sample->result->magnesium != null ? $point->sample->result->magnesium : 0 }});
        _results['salinity'].push({{ $point->sample->result->salinity != null ? $point->sample->result->salinity : 0 }});
        _results['salinity_2'].push({{ $point->sample->result->salinity != null ? $point->sample->result->salinity : 0 }});
        _results['absorbed_sum'].push({{ $point->sample->result->absorubed_sum != null ? $point->sample->result->absorubed_sum : 0 }});

        _results['id'].push({{ $point->id }});

        _points.push([{{ $point->lon }}, {{ $point->lat }}]);
    @endforeach

    console.log('_points', _points);



</script>

<!-- FUNCTIONS -->
<script>
    function addPolygon(num, coordinates) {
        // update map
        if (map.getLayer('field')) {
            map.removeLayer('field');
        }

        if (map.getLayer('outline')) {
            map.removeLayer('outline');
        }

        if (map.getSource('field')) {
            map.removeSource('field');
        }

        currentCoordinates = coordinates;

        _polygon = {
            'type': 'Feature',
            'geometry': {
                'type': 'Polygon',
                // These coordinates outline field.
                'coordinates': [coordinates]
            }
        };

        // Add a data source containing GeoJSON data.
        map.addSource('field', {
            'type': 'geojson',
            'data': _polygon
        });

        // Add a new layer to visualize the polygon.
        var fieldId = 'field';
        map.addLayer({
            'id': fieldId,
            'type': 'fill',
            'source': fieldId, // reference the data source
            'layout': {},
            'paint': {
                // 'fill-color': '#0080ff', // blue color fill
                'fill-color': getColorForValue(getMinValue(_results[_value]), _value),
                'fill-opacity': 1
            }
        });

        // Add a black outline around the polygon.
        map.addLayer({
            'id': "outline",
            'type': 'line',
            'source': fieldId,
            'layout': {},
            'paint': {
                'line-color': '#000',
                'line-width': 3
            }
        });

        map.flyTo({
            center: coordinates[0],
            zoom: 14,
            duration: 0,
        });

        // fit map
        var bounds = coordinates.reduce(function (bounds, coord) {
            return bounds.extend(coord);
        }, new mapboxgl.LngLatBounds(coordinates[0], coordinates[0]));
        
        map.fitBounds(bounds, { padding: 50 });
    }

    function drawPolygon() {
        // console.log('fill color in drawPolygon: ' + _fillColor);

        num = 1;

        // Add a data source containing GeoJSON data.
        map.addSource('field_' + num, {
            'type': 'geojson',
            'data': _polygon
        });

        // // Add a new layer to visualize the polygon.
        var fieldId = 'field_' + num;
        map.addLayer({
            'id': fieldId,
            'type': 'fill',
            'source': fieldId, // reference the data source
            'layout': {},
            'paint': {
                // 'fill-color': '#ff0000', // blue color fill
                'fill-color': _fillColor,
                'fill-opacity': _fillOpacity,
            }
        });

        // Add a black outline around the polygon.
        map.addLayer({
            'id': fieldId + "_outline",
            'type': 'line',
            'source': fieldId,
            'layout': {},
            'paint': {
                'line-color': '#000',
                'line-width': 3
            }
        });
    }

    function drawFields() {
        for (var f = 0; f < fields.length; f++) {
            var coordinates = [];

            for (var i = 0; i < fields[f].length; i++) {
                var ring = fields[f][i];

                for (var j = 0; j < ring.length; j++) {
                    // coordinates.push(ring[j]);
                    coordinates.push(meters2degrees(ring[j]));
                }
            }
            console.log(coordinates);

            addPolygon(f, coordinates);
        }
    }

    var meters2degrees = function(arr) {
        var x = arr[0];
        var y = arr[1];

        var lon = x *  180 / 20037508.34 ;
        //thanks magichim @ github for the correction
        var lat = Math.atan(Math.exp(y * Math.PI / 20037508.34)) * 360 / Math.PI - 90; 
    
        return [lon, lat];
    }

    function updatePolygon() {
        // coordinates.
    }

    var minLat = 90;
    var maxLat = -90;
    var minLng = 180;
    var maxLng = -180;

    function drawGrid() {
        var turfPolygon = undefined;

        for (var f = 0; f < 1; f++) {
            var coordinates = [];

            for (var i = 0; i < fields[0].length; i++) {
                var ring = fields[f][i];

                for (var j = 0; j < ring.length; j++) {
                    var lnglat = meters2degrees(ring[j]);
                    // var lnglat = ring[j];

                    // console.log(lnglat);

                    coordinates.push(lnglat);

                    // lat
                    if (lnglat[1] > maxLat)
                        maxLat = lnglat[1];

                    if (lnglat[1] < minLat)
                        minLat = lnglat[1];

                    // lng
                    if (lnglat[0] > maxLng)
                        maxLng = lnglat[0];

                    if (lnglat[0] < minLng)
                        minLng = lnglat[0];
                }
            }
        }

        console.log('coordinates', coordinates);

        turfPolygon = turf.polygon([coordinates], { name: "poly" });
        // console.log("turfPolygon", turfPolygon);

        _bbox = turf.bbox(turfPolygon);
        // console.log("bbox", _bbox);

        // console.log(minLat, maxLat);
        // console.log(minLng, maxLng);

        for (let lng = minLng; lng <= maxLng; lng += DIST_IN_KM) {
            graticule.features.push({
                type: 'Feature',
                geometry: {type: 'LineString', coordinates: [[lng, minLat], [lng, maxLat]]},
                properties: {value: lng}
            });
        }
        for (let lat = minLat; lat <= maxLat; lat += DIST_IN_KM) {
            graticule.features.push({
                type: 'Feature',
                geometry: {type: 'LineString', coordinates: [[minLng, lat], [maxLng, lat]]},
                properties: {value: lat}
            });
        }

        // for (let lng = minLng; lng <= maxLng; lng += DIST_IN_KM) {
        //     for (let lat = minLat; lat <= maxLat; lat += DIST_IN_KM) {
        //         var point = [lng + DIST_IN_KM / 2, lat + DIST_IN_KM / 2];
                
        //         var turfPoint = turf.point(point)

        //         if (turf.inside(turfPoint, turfPolygon)) {
        //             const marker = new mapboxgl.Marker({draggable: true})
        //                 .setLngLat(point)
        //                 .addTo(map);
                    
        //             currentPoints.push(marker);
        //         }

        //     }
        // }
        
        // points
        
        // console.log(points);
        
        // feature;
        var features = [];
        for (i = 0; i < _points.length; i++) {
            features.push({
                type: "Feature",
                geometry: {
                    type: "Point",
                    coordinates: _points[i]
                },
                properties: {
                    value: 'Гумус: ' + _results['humus'][i] + "<br>" + 
                    'pH: ' + _results['ph'][i] + "<br>" + 
                    'Фосфор: ' + _results['p'][i] + "<br>" + 
                    'сера: ' + _results['s'][i] + "<br>" + 
                    'Калий: ' + _results['k'][i] + "<br>" + 
                    'Нитраты: ' + _results['no3'][i] + "<br>",
                    id: _results['id'][i]
                }
            });
        }

        const geojson = {
            type: 'FeatureCollection',
            features: features
        };
        
        // dots
        for (const feature of geojson.features) {
            // create a HTML element for each feature
            const el = document.createElement('div');
            el.className = 'marker-' + feature.properties.id + '-' + _value;
            // console.log('className: ', el.className);

            // make a marker for each feature and add to the map
            new mapboxgl.Marker(el)
                .setPopup(new mapboxgl.Popup({ offset: 25 }).setHTML(`<h3>${feature.properties.value}</h3>`))
                .setLngLat(feature.geometry.coordinates)
                .addTo(map);
        }

        // fill color
        // _fillColor = getColorForValue(_value, getMinValue(_results[_value]));
        // console.log('fill color in drawGrids: ' + _fillColor);
        // console.log("fill color for : ", _value, _fillColor, _results, _results[_value], _value);

        // draw field
        // drawPolygon();

        // colorize
        colorizePolygon(geojson.features);
    }

    function getMinValue(values) {
        console.log('values', values);

        var min = values[0];

        for (var i in values) {
            if (min > values[i]) {
                min = values[i];
            }
        }
        console.log('min', min);

        return min;
    }

    function getColorForValue(num, value) {
        if (num == 'humus') {
            if (value <= 2) {
                return '#F0F8FF';
            } else if (value <= 4){
                return '#89CFF0';
            } else if (value <= 6) {
                return '#318CE7';
            } else if (value <= 8) {
                return '#0039a6'; 
            } else if (value <= 10) {
                return '#034694';
            } else if (value > 10) {
                return '#002D62';
            }
        } else if (num == 'ph') {
            if (value < 4.6) {
                return '#b00000';
            } else if (value < 5.1){
                return '#c41e3a';
            } else if (value < 5.5) {
                return '#ff2400';
            } else if (value < 6) {
                return '#DC143C'; 
            } else if (value < 7) {
                return '#ffe5b4';
            } else if (value < 8) {
                return '#8b00ff';
            } else if (value >=8.1 ) {
                return '#00bfff';
            }
        } else if (num == 'no3') {
            if (value < 5) {
                return '#F0E68C';
            } else if (value < 10){
                return '#FFC72C';
            } else {
                return '#FF8C00';
            }
        } else if (num == 'no3_2') {
            if (value < 10) {
                return '#F0E68C';
            } else if (value < 15){
                return '#FFC72C';
            } else {
                return '#FF8C00';
            }

        } else if (num == 'p') {
            if (value < 10) {
                return '#30D5C8';
            } else if (value < 15){
                return '#ADD8E6';
            } else if (value < 30) {
                return '#80A6FF';
            } else if (value < 45) {
                return '#4169E1';
            } else if (value < 60) {
                return '#0000ff';
            } else {
                return '#00008b';
            }
        } else if (num == 'k') {
            if (value < 100) {
                return '#ffff00';
            } else if (value < 200){
                return '#ffc93b';
            } else if (value < 300) {
                return '#ffa500';
            } else if (value < 400) {
                return '#cd853f';
            } else if (value < 600) {
                return '#964b00';
            } else {
                return '#654321';
            }
        } else if (num == 's') {
            if (value < 6) {
                return '#ffff00';
            } else if (value < 12){
                return '#9b870c';
            } else {
                return '#ffa500';
            }
        } else if (num == 'b') {
            return '#FF7F50';
        } else if (num == 'fe') {
            return '#FF6347';
        } else if (num == 'na') {
            return '#CD853F';
        } else if (num == 'cu') {
            if (value < 0.21) {
                return '#FFFF99';
            } else if (value < 0.5) {
                return '#FFFF33';
            } else {
                return '#999900';
            }
        } else if (num == 'zn') {
            if (value < 2.1) {
                return '#FFC0CB';
            } else if (value < 5.0) {
                return '#FF69B4';
            } else {
                return '#DB7093';
            }
        } else if (num == 'mn') {
            if (value < 10) {
                return '#С0С0С0';
            } else if (value < 20.0) {
                return '#808080';
            } else {
                return '#363636';
            }
        } else if (num == 'calcium') {
            if (value < 2.6) {
                return '#B0E0E6';
            } else if (value < 5.1) {
                return '#87CEFA';
            } else if (value < 10.1) {
                return '#98FB98';
            } else if (value < 15.1) {
                return '#ADFF2F';
            } else if (value < 20) {
                return '#4169E1';
            } else {
                return '#556B2F';
            }

        } else if (num == 'magnesium') {
            if (value < 0.6) {
                return '#B0E0E6';
            } else if (value < 1.1) {
                return '#87CEFA';
            } else if (value < 2.1) {
                return '#98FB98';
            } else if (value < 3.1) {
                return '#ADFF2F';
            } else if (value < 4) {
                return '#4169E1';
            } else {
                return '#556B2F';
            }

        } else if (num == 'salinity') {
            if (value < 2) {
                return '#98FB98';
            } else if (value < 4) {
                return '#32CD32';
            } else if (value < 8) {
                return '#00FF00';
            } else if (value < 16) {
                return '#228B22';
            } else {
                return '#696969';
            }

        } else if (num == 'salinity_2') {
            if (value < 4) {
                return '#98FB98';
            } else if (value < 8) {
                return '#32CD32';
            } else if (value < 16) {
                return '#00FF00';
            } else if (value < 24) {
                return '#228B22';
            } else {
                return '#696969';
            }
                
        } else if (num == 'absorbed_sum') {
            if (value < 5.1) {
                return '#DCDCDC';
            } else if (value < 10.1) {
                return '#A9A9A9';
            } else if (value < 15.1) {
                return '#696969';
            } else if (value < 20.1) {
                return '#CCCC00';
            } else if (value < 30.1) {
                return '#D2B48C';
            } else {
                return '#8B4513';
            }
        }

        return _fillColor;
    }

    function colorizePolygon(features) {
        var options = {
            bbox: _bbox
        };

        var points = [];
        for (const feature of features) {
            points.push(turf.point(feature.geometry.coordinates));
        }

        var fcol = turf.featureCollection(points);
        console.log("fcol", fcol);

        var voronoiPolygons = turf.voronoi(fcol, options);
        // console.log("voronoi", voronoiPolygons);

        for (var i = 0; i < voronoiPolygons.features.length; i++) {
            var polygon = voronoiPolygons.features[i];
            console.log('loop polygon', polygon, _polygon);

            // 1 - intersect
            var intersection = turf.intersect(_polygon, polygon);

            // 2 - smoothed
            var smoothed = turf.polygonSmooth(intersection, {iterations: 7});
            // console.log('smooth', smoothed);

            map.addSource('voronoi_' + i, {
                'type': 'geojson',
                'data': smoothed.features[0]
                // 'data': intersection
            });

            var color = '#7CB9E8';

            // гумус
            if (_value == 'humus') {
                var value = _results['humus'][i];
                // console.log('Это гумус: ' + value);
                color = getColorForValue('humus', value);
            }

            if (_value == 'ph') {
                var value = _results['ph'][i];
                color = getColorForValue('ph', value);
            }

            if (_value == 'p') {
                var value = _results['p'][i];
                color = getColorForValue('p', value);
            }

            if (_value == 's') {
                var value = _results['s'][i];
                color = getColorForValue('s', value);
            }

            if (_value == 'k') {
                var value = _results['k'][i];
                color = getColorForValue('k', value);
            }

            if (_value == 'no3') {
                var value = _results['no3'][i];
                color = getColorForValue('no3', value);
            }

            if (_value == 'no3_2') {
                var value = _results['no3_2'][i];
                color = getColorForValue('no3_2', value);
            }

            if (_value == 'b') {
                var value = _results['b'][i];
                color = getColorForValue('b', value);
            }

            if (_value == 'fe') {
                var value = _results['fe'][i];
                color = getColorForValue('fe', value);
            }

            if (_value == 'cu') {
                var value = _results['cu'][i];
                color = getColorForValue('cu', value);
            }

            if (_value == 'zn') {
                var value = _results['zn'][i];
                color = getColorForValue('zn', value);
            }

            if (_value == 'mn') {
                var value = _results['mn'][i];
                color = getColorForValue('mn', value);
            }

            if (_value == 'na') {
                var value = _results['na'][i];
                color = getColorForValue('na', value);
            }

            if (_value == 'calcium') {
                var value = _results['calcium'][i];
                color = getColorForValue('calcium', value);
            }

            if (_value == 'magnesium') {
                var value = _results['magnesium'][i];
                color = getColorForValue('magnesium', value);
            }

            if (_value == 'salinity') {
                var value = _results['salinity'][i];
                color = getColorForValue('salinity', value);
            }

            if (_value == 'salinity_2') {
                var value = _results['salinity_2'][i];
                color = getColorForValue('salinity_2', value);
            }

            if (_value == 'absorbed_sum') {
                var value = _results['absorbed_sum'][i];
                color = getColorForValue('absorbed_sum', value);
            }

            map.addLayer({
                'id': 'voronoi_' + i,
                'type': 'fill',
                'source': 'voronoi_' + i,
                'layout': {},
                'paint': {
                    'fill-color': color,
                    'fill-opacity': _fillOpacity,
                }
            });
        }

        map.addSource('voronoi', {
            'type': 'geojson',
            'data': voronoiPolygons
        });

        // map.addLayer({
        //     'id': "voronoi_outline",
        //     'type': 'line',
        //     'source': 'voronoi',
        //     'layout': {},

        //     'paint': {
        //         'line-color': '#FF0000',
        //         'line-width': 3,
        //         // 'fill-opacity': 0.3
        //     }
        // });


        // move to center
        var center = turf.centroid(voronoiPolygons.features[3]);
        console.log("center", center);

        // map.flyTo({
        //     center: center.geometry.coordinates,
        //     zoom: 16,
        //     duration: 0,
        //     // essential: true // this animation is considered essential with respect to prefers-reduced-motion
        // });


        
    }

    function markerDragStart(e) {
        currentMarker = e.target;
    }

    function markerDragEvent(e) {
        var newLngLat = e.target.getLngLat();
        var newCoordinates = [];

        for (var i = 0; i < coordinates.length; i++) {
            if (i == e.target._element.id) {
                newCoordinates.push([newLngLat.lng, newLngLat.lat]);
            } else {
                newCoordinates.push(coordinates[i]);
            }
        }

        coordinates = [];
        coordinates = newCoordinates;

        // update map
        if (map.getLayer('field')) {
            map.removeLayer('field');
        }

        if (map.getLayer('outline')) {
            map.removeLayer('outline');
        }

        if (map.getSource('field')) {
            map.removeSource('field');
        }

        // addPolygon();
    }

    function updateMap() {
        // grid
        if (showGrid == GRID.ON) {
            map.addSource('graticule', {
                type: 'geojson',
                data: graticule
            });
            map.addLayer({
                id: 'graticule',
                type: 'line',
                source: 'graticule'
            });
        } else {
            if (map.getLayer('graticule'))
                map.removeLayer('graticule');
            
            if (map.getSource('graticule'))
                map.removeSource('graticule');
        }

        // subpolygon
        if (mode == MODE.SUBPOLYGON_ON) {
            for (var i = 0; i < coordinates.length; i++) {
                const marker = new mapboxgl.Marker({draggable: true})
                    .setLngLat([coordinates[i][0], coordinates[i][1]]);
                marker._element.id = i;
                
                spMarkers.push(marker);

                marker.on('dragstart', markerDragStart);
                marker.on('dragend', markerDragEvent);
                marker.addTo(map);
            }
        } else if (mode == MODE.SUBPOLYGON_OFF) {
            spMarkers.forEach(function(marker) {
                marker.remove();
            })
        }
    }

</script>


<script>

    console.log(Math.tan(2.7183));

    var currentMarker = undefined;
    var currentCoordinates = [];
    var currentPoints = [];
    var mapMarkers = {};
    var zoomLevel = 17;

    // console.log(coordinates);

	// TO MAKE THE MAP APPEAR YOU MUST
	// ADD YOUR ACCESS TOKEN FROM
	// https://account.mapbox.com
	mapboxgl.accessToken = 'pk.eyJ1Ijoic2FuY2hvbWFuIiwiYSI6ImNqdjByNHNiZTA1Mm40NG11eWR1dTBlcXUifQ.HJ1uqIzJWWmf2VrHIVMQ5w';
    const map = new mapboxgl.Map({
        container: 'map', // container ID
        // style: 'mapbox://styles/mapbox/light-v10', // style URL
        style: 'mapbox://styles/mapbox/satellite-v9', // style URL
        layers: [
          {
            id: 'background',
            type: 'background',
            paint: { 
              'background-color': 'white' 
            }
          }
        ],
        // style: 'mapbox://styles/mapbox/light-v10', // style URL
        // center: [78.15147522185354, 43.6263221110307], // TODO: get starting position
        center: [69.301395883916, 41.49386707964521],
        zoom: zoomLevel // starting zoom
    });
    // map.addControl(new mapboxgl.NavigationControl());
    

    var _fillColor = getColorForValue(_value, getMinValue(_results[_value]));
    console.log('_fillColor', _fillColor);
    var _fillOpacity = 1;

    map.on('load', () => {

        // draw fields
        drawFields();

        // draw grid
        drawGrid();




        // map.addSource('graticule', {
        //     type: 'geojson',
        //     data: graticule
        // });
        // map.addLayer({
        //     id: 'graticule',
        //     type: 'line',
        //     source: 'graticule'
        // });
    });
</script>