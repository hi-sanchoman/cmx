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
</style>

@endsection

@section('content')
    <section class="section">
        <div class="section-header">
        <h1>@lang('models/fields.singular') (<span id="area"></span> га)</h1>
        <div class="section-header-breadcrumb">
            <!-- <a href="{{ route('fields.index') }}" class="btn btn-primary form-btn float-right">@lang('crud.back')</a> -->
        </div>
      </div>
   @include('stisla-templates::common.errors')
    <div class="section-body">
        <div class="card">
            <div class="card-header">
                <div class="btn-group" role="group" aria-label="Polygon">
                    <button type="button" id="btnPolygon" class="btn btn-secondary"><i class="fa fa-edit"></i> Полигон</button>
                    <!-- <button type="button" class="btn btn-secondary">Edit polygon</button> -->
                </div>

                
                <div class="btn-group" role="group" aria-label="Grid" style="margin-left: 30px;">
                    <button type="button" id="btnGrid" class="btn btn-secondary">Сетка</button>
                    
                    <input type="text" id="txtDistance" name="txtDistance" style="width: 120px; margin-left: 10px; margin-right: 5px;" value="1">
                    <button id="btnFiveGa" class="btn btn-secondary">5га</button>
                    <button id="btnTwentyFiveGa" class="btn btn-secondary">25га</button>
                    
                    <button type="button" id="btnDots" class="btn btn-secondary">Точки</button>
                    <button type="button" id="btnCreatePoints" class="btn btn-info" style="visibility: hidden;">Создать метки</button>
                </div>

                
                <div class="btn-group" role="group" aria-label="Points" style="margin-left: 30px;">
                    <button type="button" id="btnAddPoint" class="btn btn-danger">+ Метка</button>
                    
                </div>

                <div class="btn-group" role="group" aria-label="Kml" style="margin-left: 30px;">
                    <a type="button" href="/fields/{{ $field->id }}/kml" target="_blank" class="btn btn-success">KML</a>
                </div>

                <!-- <div style="display: inline; margin-left: 50px">
                    <span id="mode">Режим:</span>
                </div> -->
            </div>

            
            
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
    var geometry = {!! $field->polygon->geometry !!};
    // console.log(geometry);

    var graticule = {
        type: 'FeatureCollection',
        features: []
    };
    var DIST_IN_KM = 0.1/111;

    // grid
    const GRID = {
        ON: "on",
        OFF: "off"
    };

    // dots
    const DOTS = {
        ON: "on",
        OFF: "off"
    };

    // polygon
    const MODE = {
        POLYGON_ON: "polygon_on",
        POLYGON_OFF: "polygon_off"
    };

    // init values
    var showGrid = GRID.OFF;
    var showDots = DOTS.OFF;
    var mode = MODE.POLYGON_OFF;
    // console.log(showGrid);

    var spMarkers = [];
    var dotsMarkers = [];
    var redMarkers = [];

    var fields = [];
    var _points = [];

    @foreach ($fields as $field)
        fields.push({!! $field->polygon->geometry !!});
        
        @if ($field->polygon != null && $field->polygon->points != null)
            @foreach ($field->polygon->points as $point)
                _points.push({
                    lat: {!! $point->lat !!}, 
                    lng: {!! $point->lon !!},
                    id: {!! $point->id !!}
                });
            @endforeach
        @endif
    @endforeach

    console.log('fields', fields);
    // console.log('points', _points);

</script>

<!-- FUNCTIONS -->
<script>
    function addPolygon(num, coordinates, fly) {
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

        // check whether first == latest
        // if (coordinates[0] != coordinates[coordinates.length - 1]) {
        //     coordinates.push(coordinates[0]);
        // }

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

        // calculate area
        var polygonArea = turf.polygon([coordinates]);
        var area = turf.area(polygonArea);
        $('#area').html((area / 10000).toFixed(2));

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

        if (fly == true) {
            map.flyTo({
                center: coordinates[0],
                zoom: 13,
                duration: 0,
            });

            // fit map
            var bounds = coordinates.reduce(function (bounds, coord) {
                return bounds.extend(coord);
            }, new mapboxgl.LngLatBounds(coordinates[0], coordinates[0]));
            
            map.fitBounds(bounds, { padding: 50 });    
        }
    }

    function drawFields() {
        for (var f = 0; f < fields.length; f++) {
            var coordinates = [];

            for (var i = 0; i < fields[f].length; i++) {
                var ring = fields[f][i];

                for (var j = 0; j < ring.length; j++) {
                    coordinates.push(meters2degrees(ring[j]));
                }
            }
            // console.log(coordinates[0]);

            addPolygon(f, coordinates, true);
        }
    }

    function drawPoints() {
        console.log('draw points...', _points);

        for (var i = 0; i < _points.length; i++) {
            const marker = new mapboxgl.Marker({draggable: true, color: "#ff0000"})
                        .setLngLat(new mapboxgl.LngLat(_points[i].lng, _points[i].lat));
            
            marker._id = _points[i].id;
            marker.on('dragstart', redDragStart);
            marker.on('dragend', redDragEvent);
            marker.addTo(map);

            // console.log(marker);
            redMarkers.push(marker);
        }
    }

    var meters2degrees = function(arr) {
        // dd(exp(0.3));

        var x = arr[0];
        var y = arr[1];

        var lon = x * 180 / 20037508.34 ;
        //thanks magichim @ github for the correction
        var lat = Math.atan(Math.exp(y * Math.PI / 20037508.34)) * 360 / Math.PI - 90; 
    
        return [lon, lat];
    }

    function updatePolygon() {
        // coordinates.
    }

    function drawGrid(DIST_IN_KM) {
        // grid
        graticule = {
            type: 'FeatureCollection',
            features: []
        };

        // clear old
        var gr = map.getSource('graticule');

        if (map.getLayer('graticule')) {
            map.removeLayer('graticule');    
        }

        if (map.getSource('graticule')) {
            map.removeSource('graticule');
        }

        dotsMarkers.forEach(function(marker) {
            marker.remove();
        });
        dotsMarkers = [];

        // draw new
        var minLat = 90;
        var maxLat = -90;
        var minLng = 180;
        var maxLng = -180;

        var turfPolygon = undefined;

        for (var f = 0; f < 1; f++) {
            var coordinates = [];

            for (var i = 0; i < fields[0].length; i++) {
                var ring = fields[f][i];
                var innerCoordinates = [];

                console.log(ring);

                for (var j = 0; j < ring.length; j++) {
                    var lnglat = meters2degrees(ring[j]);
                    
                    // console.log(lnglat);
                    innerCoordinates.push(lnglat);

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

                coordinates.push(innerCoordinates);
            }
        }
        console.log(coordinates);

        turfPolygon = turf.polygon(coordinates, { name: "poly" });
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

        for (let lng = minLng; lng <= maxLng; lng += DIST_IN_KM) {
            for (let lat = minLat; lat <= maxLat; lat += DIST_IN_KM) {
                var point = [lng + DIST_IN_KM / 2, lat + DIST_IN_KM / 2];
                
                var turfPoint = turf.point(point)

                if (turf.inside(turfPoint, turfPolygon)) {
                    const marker = new mapboxgl.Marker({draggable: true})
                        .setLngLat(point);
                        // .addTo(map);
                    
                    currentPoints.push(marker);

                    dotsMarkers.push(marker);
                }

            }
        }
    }

    // dragging red point
    function redDragStart(e) {
        currentMarker = e.target;
        console.log('drag point', currentMarker);
    }

    function redDragEvent(e) {
        var newLngLat = e.target.getLngLat();
        console.log(newLngLat);
        // return;

        var point = [newLngLat.lng, newLngLat.lat];   
        var turfPoint = turf.point(point)
        var turfPolygon = turf.polygon([currentCoordinates], { name: "poly" });

        if (!turf.inside(turfPoint, turfPolygon)) {
            // remove point from db
            var data = {
                'ajax': 1,
                'point_id': currentMarker._id
            };

            $.post('http://185.146.3.112/plesk-site-preview/cemexlab.kz/https/185.146.3.112/delete_point', data, function (res) {
                if (res == 1) {
                    // remove from map
                    currentMarker.remove();
                }
            });
        } else {
            // update lat lng
            var data = {
                'ajax': 1,
                'point_id': currentMarker._id,
                'lat': point[1],
                'lng': point[0]
            };

            $.post('http://185.146.3.112/plesk-site-preview/cemexlab.kz/https/185.146.3.112/update_point', data, function (res) {
                if (res == 0) {
                    alert('Ошибка в базе. Попробуйте еще раз');
                }
            });
        }
    }


    function markerDragStart(e) {
        currentMarker = e.target;
    }

    function markerDragEvent(e) {
        var newLngLat = e.target.getLngLat();
        var newCoordinates = [];

        for (var i = 0; i < currentCoordinates.length; i++) {
            if (i == e.target._element.id) {
                newCoordinates.push([newLngLat.lng, newLngLat.lat]);
            } else {
                newCoordinates.push(currentCoordinates[i]);
            }
        }

        currentCoordinates = [];
        currentCoordinates = newCoordinates;

        for (var i in currentCoordinates) {
            //console.log(currentCoordinates[i][0], currentCoordinates[i][1]);
        }
        //console.log("--- END ---");

        // redraw polygon
        addPolygon(1, currentCoordinates, false);

        // update in db
        var data = {
            'coordinates': currentCoordinates,
            'field_id': {{ $field->id }}
        };

        console.log(data);

        $.post('http://185.146.3.112/plesk-site-preview/cemexlab.kz/https/185.146.3.112/update_polygon', data, function(res) {
            console.log('update polygon', res);
        });
    }

    function updateMap() {
        // grid
        var gr = map.getSource('graticule');
        console.log('gr', gr);

        if (showGrid == GRID.ON) {
            if (gr == undefined) {
                map.addSource('graticule', {
                    type: 'geojson',
                    data: graticule
                });
                map.addLayer({
                    id: 'graticule',
                    type: 'line',
                    source: 'graticule'
                });
            }
        } else {
            if (map.getLayer('graticule'))
                map.removeLayer('graticule');
            
            if (map.getSource('graticule'))
                map.removeSource('graticule');
        }

        // subpolygon
        if (mode == MODE.POLYGON_ON) {
            for (var i = 0; i < currentCoordinates.length; i++) {
                const marker = new mapboxgl.Marker({draggable: true, color: '#00FF00'})
                    .setLngLat([currentCoordinates[i][0], currentCoordinates[i][1]]);
                marker._element.id = i;
                
                spMarkers.push(marker);

                marker.on('dragstart', markerDragStart);
                marker.on('dragend', markerDragEvent);
                marker.addTo(map);
            }
        } else if (mode == MODE.POLYGON_OFF) {
            spMarkers.forEach(function(marker) {
                marker.remove();
            })
        }

        // dots
        if (showDots == DOTS.ON) {
            dotsMarkers.forEach(function(marker) {
                marker.addTo(map);
            });

        } else if (showDots == DOTS.OFF) {
            dotsMarkers.forEach(function(marker) {
                marker.remove();
            });
        }
    }

</script>


<script>
    // console.log(Math.exp(0.3));
    // console.log(Math.atan(0.75));

    var currentMarker = undefined;
    var currentCoordinates = [];
    var currentPoints = [];
    var mapMarkers = {};
    var zoomLevel = 15;


    // _coodrinates = 
    
    // console.log(coordinates);

	// TO MAKE THE MAP APPEAR YOU MUST
	// ADD YOUR ACCESS TOKEN FROM
	// https://account.mapbox.com
	mapboxgl.accessToken = 'pk.eyJ1Ijoic2FuY2hvbWFuIiwiYSI6ImNqdjByNHNiZTA1Mm40NG11eWR1dTBlcXUifQ.HJ1uqIzJWWmf2VrHIVMQ5w';
    const map = new mapboxgl.Map({
        container: 'map', // container ID
        style: 'mapbox://styles/mapbox/satellite-v9', // style URL
        // center: [69.301395883916, 41.49386707964521],
        center: [77.26416611676161, 43.73620052570794],
        zoom: zoomLevel + 2// starting zoom
    });
    map.addControl(new mapboxgl.NavigationControl());


    // draw grid
    drawGrid(DIST_IN_KM);

    map.on('load', () => {
        drawFields();

        drawPoints();

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
    var updateBtns = function() {
        // grid
        $('#btnGrid').removeClass('btn-secondary').removeClass('btn-primary');

        if (showGrid == GRID.ON)
            $('#btnGrid').addClass('btn-primary');
        else 
            $('#btnGrid').addClass('btn-secondary');
        
        // polygon
        $('#btnPolygon').removeClass('btn-secondary').removeClass('btn-primary');

        if (mode == MODE.POLYGON_ON)
            $('#btnPolygon').addClass('btn-primary');
        else 
            $('#btnPolygon').addClass('btn-secondary');

        // dots
        $('#btnDots').removeClass('btn-secondary').removeClass('btn-primary');

        if (showDots == DOTS.ON) {
            $('#btnDots').addClass('btn-primary');
            $('#btnCreatePoints').css('visibility', 'visible');
        } else { 
            $('#btnDots').addClass('btn-secondary');
            $('#btnCreatePoints').css('visibility', 'hidden');
        }
    }

    $('#btnGrid').click(function() {
        showGrid = (showGrid == GRID.ON) ? GRID.OFF : GRID.ON;
        mode = MODE.POLYGON_OFF;

        var dist = $('#txtDistance').val();
        console.log('dist', dist);

        drawGrid(dist * 3 / 2 / 10 / 111);

        updateBtns();
        updateMap();
    });

    $('#btnFiveGa').click(function() {
        $('#txtDistance').val(2.236);
    });

    $('#btnTwentyFiveGa').click(function() {
        $('#txtDistance').val(5);
    });

    $('#btnDots').click(function() {
        showDots = (showDots == DOTS.ON) ? DOTS.OFF : DOTS.ON;
        showGrid = GRID.ON;
        mode = MODE.POLYGON_OFF;

        var dist = $('#txtDistance').val();
        drawGrid(dist * 3 / 2 / 10 / 111);

        updateBtns();
        updateMap();
    });    

    $('#btnPolygon').click(function() {
        // console.log('mode', mode);
        mode = (mode == MODE.POLYGON_ON) ? MODE.POLYGON_OFF : MODE.POLYGON_ON;
        showGrid = GRID.OFF;
        showDots = DOTS.OFF;

        // console.log('mode', mode);

        updateBtns();
        updateMap();
    });


    $('#btnAddPoint').click(function() {
        var polygon = turf.polygon([currentCoordinates]);
        var centroid = turf.centroid(polygon);

        console.log(centroid.geometry.coordinates);

        var data = {
            ajax: 1,
            lat: centroid.geometry.coordinates[1],
            lon: centroid.geometry.coordinates[0],
            num: redMarkers.length + 1,
            polygon_id: {!! $field->polygon->id !!},
        };

        $.post('http://185.146.3.112/plesk-site-preview/cemexlab.kz/https/185.146.3.112/points', data).then(function(res) {
            if (res != 0) {
                // make them red
                const marker = new mapboxgl.Marker({draggable: true, color: "#ff0000"})
                    .setLngLat(new mapboxgl.LngLat(centroid.geometry.coordinates[0], centroid.geometry.coordinates[1]));

                marker._id = res;
                marker.on('dragstart', redDragStart);
                marker.on('dragend', redDragEvent);
                marker.addTo(map);

                redMarkers.push(marker);
            }
        });
    });


    $('#btnCreatePoints').click(function() {
        if (confirm("Создание новых меток удалит предыдущие метки со всеми их данными (пробы, результаты и т.д.). Вы уверенны?") == true) {
            // remove previous

            var data = {id: {!! $field->polygon->id !!}};
            console.log('polygon_id', data);

            $.post('http://185.146.3.112/plesk-site-preview/cemexlab.kz/https/185.146.3.112/clear_polygon', data).then(function(res) {
                // console.log('create points');

                if (res == 1) {
                    redMarkers.forEach(function(marker) {
                        marker.remove();
                    });

                    redMarkers = [];
                }
                
                for (let i = 0; i < dotsMarkers.length; i++) {
                    console.log(dotsMarkers[i]._lngLat);

                    var data = {
                        ajax: 1,
                        lat: dotsMarkers[i]._lngLat.lat,
                        lon: dotsMarkers[i]._lngLat.lng,
                        num: i + 1,
                        polygon_id: {!! $field->polygon->id !!},
                    };

                    $.post('http://185.146.3.112/plesk-site-preview/cemexlab.kz/https/185.146.3.112/points', data).then(function(res) {
                        if (res != 0) {
                            // make them red
                            const marker = new mapboxgl.Marker({draggable: true, color: "#ff0000"})
                                .setLngLat(new mapboxgl.LngLat(dotsMarkers[i]._lngLat.lng, dotsMarkers[i]._lngLat.lat));
                            
                            marker._id = res;
                            marker.on('dragstart', redDragStart);
                            marker.on('dragend', redDragEvent);
                            marker.addTo(map);

                            redMarkers.push(marker);

                            dotsMarkers[i].remove();
                        }
                    });
                }   
            });

            return;
        }
    });

    $('#btnCreateKml').click(function() {
        console.log('print kml');

        var coordinates = [];

        for (var i = 0; i < fields[0].length; i++) {
            var ring = fields[0][i];

            for (var j = 0; j < ring.length; j++) {
                coordinates.push(meters2degrees(ring[j]));
            }
        }

        console.log(coordinates);
    });
</script>




@endsection