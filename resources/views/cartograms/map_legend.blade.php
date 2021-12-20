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
        }

        .mapboxgl-popup {
            max-width: 200px;
        }

        .mapboxgl-popup-content {
            text-align: center;
            font-family: 'Open Sans', sans-serif;
        }
    </style>


    <style type="text/css">
        .legend {
            display: flex;
            width: 240px;
            /*margin-left: 20px;*/
            background: white;
        }

        .column-legend {
            width: 40px;
            height: 210px;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            margin-right: 5px;
        }

        .caption-legend {
            writing-mode: vertical-rl;
            text-orientation: mixed;
            /*margin-left: 10px;*/
            margin-top: 5px;
            margin-bottom: 5px;
            margin-right: 5px;
        }
    </style>

</head>
<body>

<div id="app">
    <div>
    
        <div class="legend">
            @foreach ($graduate as $column)

                <div class="column-legend" style="">
                    <div>
                        <span style="font-size: 8px;">{{ $column['plan'] }}</span>
                    </div>
                    <div style="height: {{ ($column['height'] * 100) }}px; background: {{ $column['color'] }};">
                        
                    </div>
                    <div class="caption-legend">
                        <span style="writing-mode: vertical-rl; text-orientation: mixed;">
                            {{ $column['caption'] }}
                        </span>
                    </div>
                </div>

            @endforeach
        </div>


    </div>


</div>

<script src="https://api.mapbox.com/mapbox-gl-js/v2.5.1/mapbox-gl.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@turf/turf@6/turf.min.js"></script>

