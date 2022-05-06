<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>Редактор карты</title>
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
    </style>
</head>



<body style="">
    <div id="map" style=""></div>

    <div id="controls">
        <div>
            <a href="/fields" class="btn btn-danger">Закрыть редактор</a>
        </div>

        <div style="margin-left: 60px;">
            <button id="polygon" class="btn btn-info btn-editor" data-btn="polygon">полигон</button>
            <button id="point" class="btn btn-info btn-editor" data-btn="point">метка</button>
            <button id="route" class="btn btn-info btn-editor" data-btn="route">путь</button>
        </div>

        <div style="margin-left: 60px;">
            <button class="btn btn-warning">экспорт kml</button>
        </div>

        <div style="margin-left: 60px;">
            {!! Form::select('client_id', App\Models\Client::dropdown(), null, ['class' => 'select2', 'style' => '']) !!}
            <button class="btn btn-info">привязать</button>
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
        var updateBtns = function() {
            $('.btn-editor').attr('class', '').addClass('btn btn-info btn-editor');

            if (mode == MODE.DRAW_POLYGON) {
                $('#polygon').attr('class', '').addClass('btn btn-success btn-editor');
            }

            if (mode == MODE.DRAW_POINT) {
                $('#point').attr('class', '').addClass('btn btn-success btn-editor');
            }

            if (mode == MODE.DRAW_ROUTE) {
                $('#route').attr('class', '').addClass('btn btn-success btn-editor');
            }
        }

    </script>


    <script>


        var currentMarker = undefined;
        var currentCoordinates = [];
        var currentPoints = [];
        var mapMarkers = {};
        var zoomLevel = 15;


        _coodrinates = [];
        
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

        map.on('click', (e) => {
            
            if (mode == MODE.DRAW_POINT) {
                console.log('clicked on: ' + e.latLng);
            }

        });
    </script>




    <script>
        $('.btn-editor').click(function() {
            var btn = $(this).data('btn');

            if (btn == 'polygon') {
                if (mode == MODE.DRAW_POLYGON) {
                    mode = MODE.IDLE;
                } else {
                    mode = MODE.DRAW_POLYGON;
                }    
            } else if (btn == 'point') {
                if (mode == MODE.DRAW_POINT) {
                    mode = MODE.IDLE;
                } else {
                    mode = MODE.DRAW_POINT;
                }
            } else if (btn == 'route') {
                if (mode == MODE.DRAW_ROUTE) {
                    mode = MODE.IDLE;
                } else {
                    mode = MODE.DRAW_ROUTE;
                }
            }
            

            updateBtns();
        });
    </script>

</body>

</html>