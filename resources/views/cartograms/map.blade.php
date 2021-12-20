@extends('layouts.app')

@section('title')
    @lang('models/fields.singular')  @lang('crud.details') 
@endsection

@section('page_css')

<link href="https://api.mapbox.com/mapbox-gl-js/v2.5.1/mapbox-gl.css" rel="stylesheet">

<style>
    #map {
        width: 100%;
        height: 500px;
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

@endsection

@section('content')
    <section class="section">
        <div class="section-header">
        <h1>@lang('models/fields.singular') @lang('crud.details')</h1>
        <div class="section-header-breadcrumb">
            <a href="{{ route('fields.index') }}"
                 class="btn btn-primary form-btn float-right">@lang('crud.back')</a>
        </div>
      </div>
   @include('stisla-templates::common.errors')
    <div class="section-body">
        <div class="card">
            <!-- header -->
            <div class="card-header">
                <div class="btn-group" role="group" aria-label="Polygon">
                    <a href="?value=humus" type="button" class="btn @if ($value == 'humus') btn-primary @else btn-secondary @endif btn-value" data-value="humus">Гумус</a>
                    <a href="?value=ph" type="button" class="btn @if ($value == 'ph') btn-primary @else btn-secondary @endif btn-value" data-value="ph">pH</a>
                    <a href="?value=p" type="button" class="btn @if ($value == 'p') btn-primary @else btn-secondary @endif btn-value" data-value="p">Фосфор</a>
                    <a href="?value=s" type="button" class="btn @if ($value == 's') btn-primary @else btn-secondary @endif btn-value" data-value="s">Сера</a>
                    <a href="?value=k" type="button" class="btn @if ($value == 'k') btn-primary @else btn-secondary @endif btn-value" data-value="k">Калий</a>
                    <a href="?value=no3" type="button" class="btn @if ($value == 'no3') btn-primary @else btn-secondary @endif btn-value" data-value="no3">Нитраты</a>
                </div>


                <form method="POST" action="/cartograms/{{ $cartogram->id }}/download" style="margin-left: 30px;">
                    @csrf

                    {!! Form::hidden('value', $value) !!}

                    <div class="row">
                        <div class="col-md-6">
                            <input class="form-control" type="text" name="specialist" placeholder="Исполнитель">
                        </div>
                        <div class="col-md-4">
                            <input class="form-control" type="date" name="date">
                        </div>
                        <div class="col-md-2">
                            <input type="submit" class="btn btn-primary" value="Скачать">
                        </div>
                    </div>

                </form>
            </div>

            <!-- map -->
            <div class="card-body">
                <div id="map"></div>
            </div>
        </div>
    </div>
    </section>
@endsection

@section('page_js')
<script src="https://api.mapbox.com/mapbox-gl-js/v2.5.1/mapbox-gl.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@turf/turf@6/turf.min.js"></script>
@endsection

@section('scripts')

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

    var _fillColor = '#89CFF0';
    var _fillOpacity = 1;

    // results
    @foreach ($cartogram->field->polygon->points as $point)
        _results['humus'].push({{ $point->sample->result->humus }});
        _results['ph'].push({{ $point->sample->result->ph }});
        _results['p'].push({{ $point->sample->result->p }});
        _results['s'].push({{ $point->sample->result->s }});
        _results['k'].push({{ $point->sample->result->k }});
        _results['no3'].push({{ $point->sample->result->no3 }});

        _results['b'].push({{ $point->sample->result->b }});
        _results['fe'].push({{ $point->sample->result->fe }});
        _results['cu'].push({{ $point->sample->result->cu }});
        _results['zn'].push({{ $point->sample->result->zn }});
        _results['mn'].push({{ $point->sample->result->mn }});
        _results['na'].push({{ $point->sample->result->na }});
        _results['calcium'].push({{ $point->sample->result->calcium }});
        _results['magnesium'].push({{ $point->sample->result->magnesium }});
        _results['salinity'].push({{ $point->sample->result->salinity }});
        _results['absorbed_sum'].push({{ $point->sample->result->absorubed_sum }});

        _results['id'].push({{ $point->id }});

        _points.push([{{ $point->lon }}, {{ $point->lat }}]);
    @endforeach

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

        // Add a data source containing GeoJSON data.
        map.addSource('field', {
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
        var fieldId = 'field';
        map.addLayer({
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
            zoom: 13,
            duration: 0,
        });
    }

    function drawPolygon() {
        console.log('fill color in drawPolygon: ' + _fillColor);

        num = 1;

        // Add a data source containing GeoJSON data.
        map.addSource('field_' + num, {
            'type': 'geojson',
            'data': _polygon
        });

        // Add a new layer to visualize the polygon.
        var fieldId = 'field_' + num;
        map.addLayer({
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

                    'Бор: ' + _results['b'][i] + "<br>",
                    'Железо: ' + _results['fe'][i] + "<br>",
                    'Марганец: ' + _results['mn'][i] + "<br>",
                    'Медь: ' + _results['cu'][i] + "<br>",
                    'Цинк: ' + _results['zn'][i] + "<br>",
                    'Натрий: ' + _results['na'][i] + "<br>",
                    'Кальций: ' + _results['calcium'][i] + "<br>",
                    'Магний: ' + _results['magnesium'][i] + "<br>",
                    'Общая засоленность: ' + _results['salinity'][i] + "<br>",
                    'Сумма поглощенных оснований: ' + _results['absorbed_sum'][i] + "<br>",

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
                .addTo(map);
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
        console.log('fill color in drawGrids: ' + _fillColor);

        console.log("fill color for : ", _value, _fillColor, _results, _results[_value], _value);

        // draw field
        drawPolygon();

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
        // console.log(fcol);

        var voronoiPolygons = turf.voronoi(fcol, options);
        console.log(voronoiPolygons);

        for (var i = 0; i < voronoiPolygons.features.length; i++) {
            var polygon = voronoiPolygons.features[i];
            // console.log(polygon);

            // 1 - intersect
            var intersection = turf.intersect(_polygon, polygon);

            // 2 - smoothed
            var smoothed = turf.polygonSmooth(intersection, {iterations: 3});
            console.log('smooth', smoothed);

            map.addSource('voronoi_' + i, {
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

        map.flyTo({
            center: center.geometry.coordinates,
            zoom: 16,
            duration: 0,
            // essential: true // this animation is considered essential with respect to prefers-reduced-motion
        });


        
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
    map.addControl(new mapboxgl.NavigationControl());
    
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


<script>
    $('.btn-value').click(function() {
        var value = $(this).data('value');
        console.log(value);
    })
</script>



@endsection