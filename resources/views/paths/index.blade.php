@extends('layouts.app')

@section('title')
     @lang('models/paths.plural')
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
            <h1>@lang('models/paths.plural')</h1>
            <div class="section-header-breadcrumb">
                <!-- <a href="{{ route('paths.create')}}" class="btn btn-primary form-btn">@lang('crud.add_new')<i class="fas fa-plus"></i></a> -->
            </div>
        </div>
    <div class="section-body">
       <div class="card">
            <div class="card-header">
                <!-- <button id="btnLogin">Login</button> -->

                <select id="units"></select>
                <button id="btnShowTrack">Показать маршрут</button>

                <div style="display: inline-block; margin-left: 40px"></div> 

                <input type="date" id="date_start">
                <input type="date" id="date_end">
                <button id="btnKml">Скачать путь</button>
            </div>

            <div class="card-body">
                <div id="map"></div>

                <div id="list">
                    @include('paths.table')
                </div>
            </div>
       </div>
   </div>
    
    </section>
@endsection



@section('page_js')

<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.2/leaflet.css" />
<script src="//cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.2/leaflet.js"></script>

<script src="https://api.mapbox.com/mapbox-gl-js/v2.5.1/mapbox-gl.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@turf/turf@6/turf.min.js"></script>
<script type="text/javascript" src="https://hst-api.wialon.com/wsdk/script/wialon.js"></script>


<script type="text/javascript">
    // global values
    var _map, _markers = {}, _tileLayer, _layers = {};
    
    // Print message to log
    function msg(text) { console.log(text); }

    // Login to server using entered username and password
    function login() {
        var sess = wialon.core.Session.getInstance(); // get instance of current Session
        var user = sess.getCurrUser(); // get current User
        if (user) { // if user exists - you are already logged, print username to log
            msg("You are logged as '" + user.getName()+"', click logout button first");
            return; 
        }
      
        // if not logged
        var token = "076f78b665e849ddffe9e4e75b5b900cE34864ABF94754C589024C85ABC01F39CFE56A36"; // get token from input
        if (!token) { // if token is empty - print message to log
            msg("Enter token");
            return;
        } 

        msg("Trying to login with token '"+ token +"'");
        sess.initSession("https://hst-api.wialon.com"); // initialize Wialon session
        sess.loginToken(token, "", // trying login 
            function (code) { // login callback
                if (code) msg(wialon.core.Errors.getErrorText(code)); // login failed, print error
                else msg("Logged successfully"); // login succeed

                // init map
                initMap();

                // load units
                getUnits();
            }
        );
    }

    function getUser() {
        var user = wialon.core.Session.getInstance().getCurrUser(); // get current user
        // print message 
        if (!user) msg("You are not logged, click 'login' button"); // user not exists
        else msg("You are logged as '" + user.getName() + "'"); // print current user name
    }

    function getUnits() { // Execute after login succeed
        var sess = wialon.core.Session.getInstance(); // get instance of current Session
        // flags to specify what kind of data should be returned
        var flags = wialon.item.Item.dataFlag.base | wialon.item.Unit.dataFlag.lastMessage;

        sess.loadLibrary("itemIcon"); // load Icon Library  
        sess.updateDataFlags( // load items to current session
            [{type: "type", data: "avl_unit", flags: flags, mode: 0}], // Items specification
            function (code) { // updateDataFlags callback
                if (code) { msg(wialon.core.Errors.getErrorText(code)); return; } // exit if error code

                // get loaded 'avl_unit's items  
                var units = sess.getItems("avl_unit");
                if (!units || !units.length){ msg("Units not found"); return; } // check if units found

                for (var i = 0; i< units.length; i++){ // construct Select object using found units
                    var u = units[i]; // current unit in cycle
                    // append option to select
                    $("#units").append("<option value='"+ u.getId() +"'>"+ u.getName()+ "</option>");
                }
                // bind action to select change event
                $("#units").change( getSelectedUnitInfo );
            }
        );
    }


    function getSelectedUnitInfo() { // print information about selected Unit

        var val = $("#units").val(); // get selected unit id
        if (!val) return; // exit if no unit selected
        
        var unit = wialon.core.Session.getInstance().getItem(val); // get unit by id
        if (!unit) { msg("Unit not found"); return; } // exit if unit not found
        
        // construct message with unit information
        var text = "<div>'" + unit.getName() + "' selected. "; // get unit name
        var icon = unit.getIconUrl(32); // get unit Icon url
        
        if (icon) text = "<img class='icon' src='" + icon + "' alt='icon'/>" + text; // add icon to message
        
        var pos = unit.getPosition(); // get unit position
        
        if (pos) { // check if position data exists
            var time = wialon.util.DateTime.formatTime(pos.t);
            text += "<b>Last message</b> "+ time + "<br/>"+ // add last message time
                "<b>Position</b> "+ pos.x + ", " + pos.y + "<br/>" + // add info about unit position
                "<b>Speed</b> "+ pos.s; // add info about unit speed
            
            // try to find unit location using coordinates 
            wialon.util.Gis.getLocations([{ lon:pos.x, lat:pos.y }], function(code, address) { 
                if (code) { msg(wialon.core.Errors.getErrorText(code)); return; } // exit if error code
                msg(text + "<br/><b>Location of unit</b>: " + address + "</div>"); // print message to log
            });
        } else // position data not exists, print message
            msg(text + "<br/><b>Location of unit</b>: Unknown</div>");
    }


    function showTrack () {
        // delete previous Tracks
        deleteTracks();

        var unit_id =  $("#units").val(),
            sess = wialon.core.Session.getInstance(), // get instance of current Session    
            renderer = sess.getRenderer(),
            cur_day = new Date(),
            from = Math.round(new Date(cur_day.getFullYear(), cur_day.getMonth(), cur_day.getDate()) / 1000), // get begin time - beginning of day
            to = from + 3600 * 24 - 1, // end of day in seconds
            unit = sess.getItem(unit_id), // get unit by id
            color = "0000ff"; // track color

            if (!unit) return; // exit if no unit

            // check the existence info in table of such track 
            if (document.getElementById(unit_id))
            {
                msg("You already have this track.");
                return;
            }
          
            var pos = unit.getPosition(); // get unit position
            if (!pos) return; // exit if no position

            // callback is performed, when messages are ready and layer is formed
            callback = qx.lang.Function.bind(function(code, layer) {
                if (code) { msg(wialon.core.Errors.getErrorText(code)); return; } // exit if error code
                
                if (layer) { 
                    var layer_bounds = layer.getBounds(); // fetch layer bounds
                    if (!layer_bounds || layer_bounds.length != 4 || (!layer_bounds[0] && !layer_bounds[1] && !layer_bounds[2] && !layer_bounds[3])) // check all bounds terms
                        return;
                    
                    // if map existence, then add tile-layer and marker on it
                    if (_map) {
                       //prepare bounds object for map
                        var bounds = new L.LatLngBounds(
                            L.latLng(layer_bounds[0],layer_bounds[1]),
                            L.latLng(layer_bounds[2],layer_bounds[3])
                        );
                        _map.fitBounds(bounds); // get center and zoom
                        
                        // create tile-layer and specify the tile template
                        if (!_tileLayer)
                            _tileLayer = L.tileLayer(sess.getBaseUrl() + "/adfurl" + renderer.getVersion() + "/avl_render/{x}_{y}_{z}/"+ sess.getId() +".png", {zoomReverse: true, zoomOffset: -1}).addTo(_map);
                        else 
                            _tileLayer.setUrl(sess.getBaseUrl() + "/adfurl" + renderer.getVersion() + "/avl_render/{x}_{y}_{z}/"+ sess.getId() +".png");
                        
                        // push this layer in global container
                        _layers[unit_id] = layer;

                        console.log(layer);
                        
                        // get icon
                        var icon = L.icon({ iconUrl: unit.getIconUrl(24) });
                        //create or get marker object and add icon in it
                        var marker = L.marker({lat: pos.y, lng: pos.x}, {icon: icon}).addTo(_map);
                        
                        marker.setLatLng({lat: pos.y, lng: pos.x}); // icon position on map
                        marker.setIcon(icon); // set icon object in marker
                        _markers[unit_id] = marker;      
                    }
                    

                }
        });
        
        // query params
        params = {
            "layerName": "route_unit_" + unit_id, // layer name
            "itemId": unit_id, // ID of unit which messages will be requested
            "timeFrom": from, //interval beginning
            "timeTo": to, // interval end
            "tripDetector": 0, //use trip detector: 0 - no, 1 - yes
            "trackColor": color, //track color in ARGB format (A - alpha channel or transparency level)
            "trackWidth": 5, // track line width in pixels
            "arrows": 0, //show course of movement arrows: 0 - no, 1 - yes
            "points": 1, // show points at places where messages were received: 0 - no, 1 - yes
            "pointColor": color, // points color
            "annotations": 0 //show annotations for points: 0 - no, 1 - yes
        };
        renderer.createMessagesLayer(params, callback);
    }


    function deleteTracks() {
        var sess = wialon.core.Session.getInstance();
        var renderer = sess.getRenderer();
        
        console.log(_layers);
        for (const [key, value] of Object.entries(_layers)) {
            console.log("layer", key, value);

            if (_layers && _layers[key])
            {
                // delete layer from renderer
                renderer.removeLayer(_layers[key], function(code) { 
                    if (code) 
                        msg(wialon.core.Errors.getErrorText(code)); // exit if error code
                    else 
                        msg("Track removed."); // else send message, then ok
                });

                delete _layers[key]; // delete layer from container
            }

            // move marker behind bounds
            if (_map)
                _map.removeLayer(_markers[key]);
            
            delete _markers[key];
        }        
    }


    function initMap() {
        // create a map in the "map" div, set the view to a given place and zoom
        _map = L.map('map').setView([43.22129567495435, 76.9361784270476], 17);
        
        var sess = wialon.core.Session.getInstance(); // get instance of current Session    
        
        // add WebGIS tile layer
        L.tileLayer(sess.getBaseGisUrl("render") + "/gis_render/{x}_{y}_{z}/" + sess.getCurrUser().getId() + "/tile.png", {
            zoomReverse: true, 
            zoomOffset: -1
        }).addTo(_map);
    }


    function loadMessages() { // load messages function
        var sess = wialon.core.Session.getInstance(); // get instance of current Session    
        var to = sess.getServerTime(); // get ServerTime, it will be end time
        var from = to - 3600*24; // get begin time ( end time - 24 hours in seconds )
        
        console.log($('#date_start').val(), $('#date_end').val());

        var unit = $("#units").val(); // get selected unit id
        if (!unit) { msg("Select unit first"); return; } // exit if no unit selected
        
        var ml = sess.getMessagesLoader(); // get messages loader object for current session
        ml.loadInterval(unit, from, to, 0, 0, 100, // load messages for given time interval
            function(code, data) { // loadInterval callback
                if (code) { msg(wialon.core.Errors.getErrorText(code)); return; } // exit if error code
                else { 
                    msg(data.count +" messages loaded. Click 'Show messages'"); 

                    printMessages(ml, unit, from, to, data.count);
                    // console.log(data);

                } // print success message 
            }
        );       
    }

    function printMessages(ml, unit, from, to, count) {
        var ml = wialon.core.Session.getInstance().getMessagesLoader();

        ml.loadInterval(unit, from, to, 0, 0, count, // load messages for given time interval
            function(code, data) { // loadInterval callback
                if (code) { msg(wialon.core.Errors.getErrorText(code)); return; } // exit if error code
                else { 
                    msg(data.count +" messages loaded. Click 'Show messages'"); 
                    // console.log(data);

                    var messages = data.messages;
                    // console.log(messages);

                    var res = [];

                    for (var i = 0; i < data.count; i++) {
                        if (messages[i].pos !== undefined) {
                            var y = messages[i].pos.y;
                            var x = messages[i].pos.x;

                            res.push([y, x]);
                        }
                    }

                    console.log(res);

                    var theData = {
                        date_end: $('#date_end').val(),
                        date_start: $('#date_start').val(),
                        unit: wialon.core.Session.getInstance().getItem(unit).getName(),
                        path: JSON.stringify(res)
                    }

                    $.post('http://185.146.3.112/plesk-site-preview/cemexlab.kz/https/185.146.3.112/public/save_route', theData, function(result) {

                        if (result == 1) {
                            // location.href = '/paths';
                            location.reload();
                        }

                    });
                } // print success message 
            }
        );
    }


    // START HERE
    $(document).ready(function() {
        login();
        // getUser();

        $('#btnLogin').click(getUser);
        $('#btnShowTrack').click(showTrack);
        $('#btnKml').click(loadMessages);

        // init map
        // var zoomLevel = 13;

        // mapboxgl.accessToken = 'pk.eyJ1Ijoic2FuY2hvbWFuIiwiYSI6ImNqdjByNHNiZTA1Mm40NG11eWR1dTBlcXUifQ.HJ1uqIzJWWmf2VrHIVMQ5w';
        // _map = new mapboxgl.Map({
        //     container: 'map', // container ID
        //     style: 'mapbox://styles/mapbox/light-v10', // style URL
        //     // style: 'mapbox://styles/mapbox/satellite-v9', // style URL
        //     // center: [69.301395883916, 41.49386707964521],
        //     center: [76.9361784270476, 43.22129567495435],
        //     zoom: zoomLevel + 2// starting zoom
        // });
        // _map.addControl(new mapboxgl.NavigationControl());
    
        // _map.on('load', function() {
        //     getUnits();

        // });
    });




</script>

@endsection