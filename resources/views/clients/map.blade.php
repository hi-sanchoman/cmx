<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>Карты клиента</title>
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

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <link href="https://api.mapbox.com/mapbox-gl-js/v2.5.1/mapbox-gl.css" rel="stylesheet">

    <style>
        html {
            width: 100%;
            height: 100%;
        }

        body {
            width: 100%;
            height: 100%;
            position: relative;
        }

        #map {
            width: 100%;
            height: 100%;
        }

        #controls {
            background-color: #FFF;

            margin: 20px;
            padding: 20px;
            position: absolute;
            z-index: 999;
            /*width: 100%;*/
            /*height: 60px;*/
            top: 0;
            display: flex;
        }


        .mapboxgl-popup {
            max-width: 200px;
        }

        h3 {
            font-size: 14px;
            text-align: left;
        }

        .mapboxgl-popup-content {
            text-align: center;
            font-family: 'Open Sans', sans-serif;
        }

        @foreach ($fields as $field)
            @foreach ($field['images'] as $id => $ms)
                @foreach ($ms as $v => $m)
                    
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
                    
                @endforeach
            @endforeach
        @endforeach
    </style>
</head>



<body style="visibility: hidden;">
    <div id="map" style=""></div>

    <div id="controls">
        <div style="margin-left: 10px;">
            <select id="value_picker">
                <option value="humus" @if ($value == 'humus') selected @endif>гумус</option>
                <option value="ph" @if ($value == 'ph') selected @endif>ph</option>
                <option value="p" @if ($value == 'p') selected @endif>фосфор</option>
                <option value="s" @if ($value == 's') selected @endif>сера</option>
                <option value="k" @if ($value == 'k') selected @endif>калий</option>
                <option value="no3" @if ($value == 'no3') selected @endif>нитраты</option>

                <option value="b" @if ($value == 'b') selected @endif>бор</option>
                <option value="fe" @if ($value == 'fe') selected @endif>железо</option>
                <option value="mn" @if ($value == 'mn') selected @endif>марганец</option>
                <option value="cu" @if ($value == 'cu') selected @endif>медь</option>
                <option value="zn" @if ($value == 'zn') selected @endif>цинк</option>
                <option value="na" @if ($value == 'na') selected @endif>натрий</option>

                <option value="calcium" @if ($value == 'calcium') selected @endif>кальций</option>
                <option value="magnesium" @if ($value == 'magnesium') selected @endif>магний</option>
                <option value="salinity" @if ($value == 'salinity') selected @endif>общая засоленность</option>
                <option value="absorbed_sum" @if ($value == 'absorbed_sum') selected @endif>сумма поглощенных оснований</option>
            </select>
            <button id="btnShow" class="btn btn-info"> показать</button>

            {!! Form::select('field_id', App\Models\Client::fieldsDropdown($client), null, ['class' => 'select2', 'style' => '', 'id' => 'field_id']) !!}
            <button id="btnFlyTo" class="btn btn-info"> перейти</button>
        </div>
    </div>

    <!-- SCRIPTS -->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/sweetalert.min.js') }}"></script>
    <script src="{{ asset('assets/js/iziToast.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.nicescroll.js') }}"></script>

    <!-- Template JS File -->
    <script src="{{ asset('web/js/stisla.js') }}"></script>
    <script src="{{ asset('web/js/scripts.js') }}"></script>
    <script src="{{ mix('assets/js/profile.js') }}"></script>
    <script src="{{ mix('assets/js/custom/custom.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="https://api.mapbox.com/mapbox-gl-js/v2.5.1/mapbox-gl.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@turf/turf@6/turf.min.js"></script>

    <!-- VAR -->
    <script>
        const DIST_IN_KM = 0.1/111;

        // grid
        const GRID = {
            ON: "on",
            OFF: "off"
        };

        // subpolygons
        const MODE = {
            IDLE: "idle",
            DRAW_POLYGON: "draw_polygon",
            DRAW_POINT: "draw_point",
            DRAW_ROUTE: "draw_route",
        };

        // init values
        var showGrid = GRID.OFF;
        var mode = MODE.IDLE;
        // console.log(showGrid);

        var spMarkers = [];
        var fields = [];


    </script>

    <!-- FUNCTIONS -->
    <script>
        var currentMarker = undefined;
        var currentCoordinates = [];
        var currentPoints = [];
        var mapMarkers = {};
        var zoomLevel = 15;

        var _map;

        _coodrinates = [];

        // fields
        var _fields = [];
        var _points = [];
        var _polygons = [];

        const graticule = {
            type: 'FeatureCollection',
            features: []
        };

        // fill fields from db
        @foreach ($fields as $field)
            _fields.push({
                id: {!! $field['field']->id !!},
                geometry: {!! $field['field']->polygon->geometry !!},
                cartogram: {!! json_encode($field['cartogram']->toArray()) !!},
                points: {!! json_encode($field['points']) !!},
                results: {!! json_encode($field['results']) !!},
                value: '{!! $field['value'] !!}'
            });
            
            {{-- @if ($field->polygon != null && $field->polygon->points != null)
                @foreach ($field->polygon->points as $point)
                    _points.push({
                        lat: {!! $point->lat !!}, 
                        lng: {!! $point->lon !!},
                        id: {!! $point->id !!}
                    });
                @endforeach
            @endif--}}
        @endforeach
    </script>




    <script>
        var meters2degrees = function(arr) {
            // dd(exp(0.3));

            var x = arr[0];
            var y = arr[1];

            var lon = x * 180 / 20037508.34 ;
            //thanks magichim @ github for the correction
            var lat = Math.atan(Math.exp(y * Math.PI / 20037508.34)) * 360 / Math.PI - 90; 
        
            return [lon, lat];
        }

        function pwd() {
            // console.log('ask for password');
            
            // var ask = '';

            // ask = prompt("Введите пароль от кабинета: ");
        
            // switch (ask) {
            //   case "{!! $client->password !!}":
            //     work();
            //     break;
            //   default:
            //     deny();
            // }
        }

        function deny() {
            alert('Не правильный пароль');
            pwd();
        }

        function initMap() {
            mapboxgl.accessToken = 'pk.eyJ1Ijoic2FuY2hvbWFuIiwiYSI6ImNqdjByNHNiZTA1Mm40NG11eWR1dTBlcXUifQ.HJ1uqIzJWWmf2VrHIVMQ5w';
            
            _map = new mapboxgl.Map({
                container: 'map', // container ID
                style: 'mapbox://styles/mapbox/satellite-v9', // style URL
                // center: [69.301395883916, 41.49386707964521],
                center: [77.26416611676161, 43.73620052570794],
                zoom: zoomLevel + 2// starting zoom
            });
            
            _map.addControl(new mapboxgl.NavigationControl());

            _map.on('click', (e) => {
                if (mode == MODE.DRAW_POINT) {
                    console.log('clicked on: ' + e.latLng);
                }
            });

            _map.on('load', () => {
                drawFields();

                flyTo(_fields[0]);
            });
        }

        function addPolygon(num, coordinates, fly) {        
            // currentCoordinates = coordinates;

            var fieldId = 'field_' + num;

            // Add a data source containing GeoJSON data.
            _map.addSource(fieldId, {
                'type': 'geojson',
                'data': {
                    'type': 'Feature',
                    'geometry': {
                        'type': 'Polygon',
                        // These coordinates outline field.
                        'coordinates': [coordinates]
                    }
                }
            });

            // Add a new layer to visualize the polygon.
            
            _map.addLayer({
                'id': fieldId,
                'type': 'fill',
                'source': fieldId, // reference the data source
                'layout': {},
                'paint': {
                    'fill-color': '#0080ff', // blue color fill
                    'fill-opacity': 0.5
                }
            });

            // Add a black outline around the polygon.
            _map.addLayer({
                'id': "outline_" + num,
                'type': 'line',
                'source': fieldId,
                'layout': {},
                'paint': {
                    'line-color': '#000',
                    'line-width': 3
                }
            });

            if (fly == true) {
                _map.flyTo({
                    center: coordinates[0],
                    zoom: 13,
                    duration: 0,
                });

                // fit map
                var bounds = coordinates.reduce(function (bounds, coord) {
                    return bounds.extend(coord);
                }, new mapboxgl.LngLatBounds(coordinates[0], coordinates[0]));
                
                _map.fitBounds(bounds, { padding: 50 });    
            }
        }

        function flyTo(field) {
            console.log('fly to field', field);

            var coordinates = [];

            for (var i = 0; i < field.geometry.length; i++) {
                var ring = field.geometry[i];

                for (var j = 0; j < ring.length; j++) {
                    coordinates.push(meters2degrees(ring[j]));
                }
            }

            // fit map
            var bounds = coordinates.reduce(function (bounds, coord) {
                return bounds.extend(coord);
            }, new mapboxgl.LngLatBounds(coordinates[0], coordinates[0]));
            
            _map.fitBounds(bounds, { padding: 200 });   

            
        }

        function drawFields() {
            for (var f = 0; f < _fields.length; f++) {
                // if (f > 5) break;

                var coordinates = [];

                for (var i = 0; i < _fields[f].geometry.length; i++) {
                    var ring = _fields[f].geometry[i];

                    for (var j = 0; j < ring.length; j++) {
                        coordinates.push(meters2degrees(ring[j]));
                    }
                }
                // console.log(coordinates[0]);

                addPolygon(f, coordinates, false);

                drawCartogram(_fields[f]);
            }
        }

        function makeProperties(i) {
            var text = 'Гумус: ' + _results['humus'][i] + "<br>" + 
                        'pH: ' + _results['ph'][i] + "<br>" + 
                        'Фосфор: ' + _results['p'][i] + "<br>" + 
                        'сера: ' + _results['s'][i] + "<br>" + 
                        'Калий: ' + _results['k'][i] + "<br>" + 
                        'Нитраты: ' + _results['no3'][i] + "<br>";

            if (_results['b'][i] != 0 && _results['fe'][i] != 0 && _results['mn'][i] != 0 && _results['cu'][i] != 0 && _results['zn'][i] != 0 && _results['na'][i] != 0 && _results['calcium'][i] != 0 && _results['magnesium'][i] != 0 && _results['salinity'][i] != 0 && _results['absorbed_sum'][i] != 0) {

                text += 'Бор: ' + _results['b'][i] + "<br>" +
                        'Железо: ' + _results['fe'][i] + "<br>" +
                        'Марганец: ' + _results['mn'][i] + "<br>" +
                        'Медь: ' + _results['cu'][i] + "<br>" +
                        'Цинк: ' + _results['zn'][i] + "<br>" +
                        'Натрий: ' + _results['na'][i] + "<br>" +
                        'Кальций: ' + _results['calcium'][i] + "<br>" +
                        'Магний: ' + _results['magnesium'][i] + "<br>" +
                        'Общая засоленность: ' + _results['salinity'][i] + "<br>" +
                        'Сумма поглощенных оснований: ' + _results['absorbed_sum'][i] + "<br>";
            }

            return text;
        }

        var minLat = 90;
        var maxLat = -90;
        var minLng = 180;
        var maxLng = -180;

        function drawCartogram(field) {
            console.log('draw cartogram...', field);
            // return;

            var _bbox;
            var _points = [];
            _results = {
                'humus': [],
                'ph': [],
                'p': [],
                's': [],
                'k': [],
                'no3': [],
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

            _value = field.value;

            var _fillColor = '#89CFF0';
            var _fillOpacity = 1;



            // results
            
            field.cartogram.field.polygon.points.forEach( function(point) {
                console.log('point', point);
                console.log('results', _results);

                _results['humus'].push(point.sample.result.humus);
                _results['ph'].push(point.sample.result.ph);
                _results['p'].push(point.sample.result.p);
                _results['s'].push(point.sample.result.s);
                _results['k'].push(point.sample.result.k);
                _results['no3'].push(point.sample.result.no3);

                _results['b'].push(point.sample.result.b);
                _results['fe'].push(point.sample.result.fe);
                _results['cu'].push(point.sample.result.cu);
                _results['zn'].push(point.sample.result.zn);
                _results['mn'].push(point.sample.result.mn);
                _results['na'].push(point.sample.result.na);
                _results['calcium'].push(point.sample.result.calcium);
                _results['magnesium'].push(point.sample.result.magnesium);
                _results['salinity'].push(point.sample.result.salinity);
                _results['absorbed_sum'].push(point.sample.result.absorbed_sum);

                _results['id'].push(point.id);

                _points.push([point.lon, point.lat]);
            });
            

            var turfPolygon = undefined;

            var coordinates = [];

            for (var i = 0; i < field.geometry.length; i++) {
                var ring = field.geometry[i];

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

            var _polygon = {
                'type': 'Feature',
                'geometry': {
                    'type': 'Polygon',
                    // These coordinates outline field.
                    'coordinates': [coordinates]
                }
            };

            turfPolygon = turf.polygon([coordinates], { name: "poly" });
            console.log(turfPolygon);

            _bbox = turf.bbox(turfPolygon);
            console.log("bbox", _bbox);

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
            for (var i = 0; i < _points.length; i++) {
                features.push({
                    type: "Feature",
                    geometry: {
                        type: "Point",
                        coordinates: _points[i]
                    },
                    properties: {
                        value: makeProperties(i),

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
                console.log('className: ', el.className);

                // make a marker for each feature and add to the map
                new mapboxgl.Marker(el)
                    .setPopup(new mapboxgl.Popup({ offset: 25 }).setHTML(`<h3>${feature.properties.value}</h3>`))
                    .setLngLat(feature.geometry.coordinates)
                    .addTo(_map);
            }


            // min Values
            var minhumus = getMinValue(_results['humus']);
            console.log('for humus', minhumus);
            // var minph = getMinValue(_results['ph']);
            // var minp = getMinValue(_results['p']);
            // var mins = getMinValue(_results['s']);
            // var mink = getMinValue(_results['k']);
            // var minno3 = getMinValue(_results['no3']);

            _fillColor = getColorForValue(_value, getMinValue(_results[_value]));
            var _fillOpacity = 1;

            console.log('fill color in drawGrids: ' + _fillColor);

            console.log("fill color for : ", _value, _fillColor, _results, _results[_value], _value);

            // draw field
            // drawPolygon();

            // colorize
            colorizePolygon(field.id, geojson.features, _bbox, _polygon, _fillOpacity);
        }

        function getMinValue(values) {
            console.log('values', values);

            var min = values[0];

            for (var i in values) {
                if (min > values[i]) {
                    min = values[i];
                }
            }

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
                } else {
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
                } else if (value < 8.1) {
                    return '#8b00ff';
                } else {
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

        function drawPolygon(num) {
            console.log('fill color in drawPolygon: ' + _fillColor);

            // Add a data source containing GeoJSON data.
            map.addSource('field_' + num, {
                'type': 'geojson',
                'data': _polygon
            });

            // Add a new layer to visualize the polygon.
            var fieldId = 'field_' + num;
            _map.addLayer({
                'id': fieldId,
                'type': 'fill',
                'source': fieldId, // reference the data source
                'layout': {},
                'paint': {
                    // 'fill-color': '#0080ff', // blue color fill
                    'fill-color': _fillColor,
                    'fill-opacity': _fillOpacity,
                }
            });

            // Add a black outline around the polygon.
            _map.addLayer({
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

        function colorizePolygon(id, features, bbox, main_polygon, fillOpacity) {
            var options = {
                bbox: bbox
            };

            var points = [];
            for (const feature of features) {
                points.push(turf.point(feature.geometry.coordinates));
            }

            var fcol = turf.featureCollection(points);
            // console.log(fcol);

            var voronoiPolygons = turf.voronoi(fcol, options);
            console.log(voronoiPolygons);

            for (var i = 0; i < voronoiPolygons.features.length; i++) {
                var polygon = voronoiPolygons.features[i];
                // console.log(polygon);

                // 1 - intersect
                var intersection = turf.intersect(main_polygon, polygon);

                // 2 - smoothed
                var smoothed = turf.polygonSmooth(intersection, {iterations: 3});
                console.log('smooth', smoothed);

                _map.addSource('voronoi_' + id + '-' + i, {
                    'type': 'geojson',
                    'data': smoothed.features[0]
                    // 'data': intersection
                });

                var color = '#7CB9E8';

                // гумус
                if (_value == 'humus') {
                    var value = _results['humus'][i];
                    console.log('Это гумус: ' + value);
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

                _map.addLayer({
                    'id': 'voronoi_' + id + '-' + i,
                    'type': 'fill',
                    'source': 'voronoi_' + id + '-' + i,
                    'layout': {},
                    'paint': {
                        'fill-color': color,
                        'fill-opacity': fillOpacity,
                    }
                });
            }

            _map.addSource('voronoi_' + id, {
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
            
        }

        function work() {
            $('body').css('visibility', 'visible');
            initMap();
        }

        $('#btnFlyTo').click(function() {
            var fieldId = $('#field_id').val();
        
            for (var i = 0; i < _fields.length; i++) {
                if (fieldId == _fields[i].id) {
                    flyTo(_fields[i]);
                }
            }
        });

        $('#btnShow').click(function() {
            var value = $('#value_picker').val();

            location.href = '/clients/' + {!! $client->id !!} + '/cabinet?token={{ $token }}' + '&value=' + value;
        })

        // START HERE
        // pwd();
        work();
    </script>

</body>

</html>