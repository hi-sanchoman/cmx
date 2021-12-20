<html>

<head>
    <title>Картограмма</title>
    <meta name='viewport' content='initial-scale=1,maximum-scale=1,user-scalable=no' />
    <script src="https://unpkg.com/deck.gl@latest/dist.min.js"></script>
    <script src="https://api.tiles.mapbox.com/mapbox-gl-js/v1.1.0-beta.1/mapbox-gl.js"></script>
    <link rel="stylesheet" type="text/css" href="https://api.tiles.mapbox.com/mapbox-gl-js/v1.1.0-beta.1/mapbox-gl.css">
    <!-- <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"> -->
    <script src="https://d3js.org/d3.v5.min.js"></script>
    
    <style type="text/css">

    body {
        background-color: white;
        font-family: DejaVu Sans;
        font-size: 12px;
    }
    
    .container {
        /*width: 100%;*/
        margin: 30px;
        border:  2px solid green;
    }

    .header {
        height: 260px;
        border-bottom:  2px solid green;
    }

    .footer {
        height: 110px;
        border-top: 2px solid green;
    }


    h1 {
        color: #0000FF;
        font-size: 15px;
        display: inline-block;
        margin-top: 20px;
        margin-right: 60px;
    }

    .row {
        display: block;
    }

    .table { display: table; width: 100%; border-collapse: collapse; }
    .table-row { display: table-row; }
    .table-cell { display: table-cell; border: 1px solid black; padding: 1em; }

    .col {
        float: left;
    }
    </style>
</head>

<body>
    
    <div class="container">
        
        <div class="header">
            <div class="row">
                <div class="col">
                    <img src="img/cemex_logo.jpg" style="width: 100px; margin: 25px;">
                </div>
                <div class="col">
                    <h1 style="font-size: 18px; padding-right: 120px;">{{ $title }}</h1>
                </div>
            </div>

            <div style="clear:both;"></div>

            <div class="row" style="margin-top: 15px;">
                <table class="" style="margin-left: 40px;">
                    <tr>
                        <td width="160px">Заказчик: </td>
                        <td>{{ $client->khname }}</td>
                    </tr>
                    <tr>
                        <td>Населенный пункт: </td>
                        <td>{{ $field->address }}</td>
                    </tr>
                    <tr>
                        <td>Поле</td>
                        <td>{{ $field->num }}, кад. номер: {{ $field->cadnum }}</td>
                    </tr>
                    <tr>
                        <td>Площадь: </td>
                        <td>{{ $field->square }}</td>
                    </tr>
                    <tr>
                        <td>Лаборатория: </td>
                        <td>ТОО "CemEX Engineering"</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="content" style="padding-top: 20px; padding-bottom: 20px;">
            
            <img src="img/map/cartograms/{{ $id }}-{{ $value }}.png" style="width: 500px; height: 500px; margin-left: 65px;">

        </div>

        <div class="footer" style="padding-top: 10px;">
            <div class="row">
                <div class="col">
                    @if (!in_array($value, ['b', 'fe', 'na']))
                    <img src="img/map/legends/{{ $id }}-{{ $value }}.png" style="width: 200px; height: 105px; margin-left: 20px; margin-right: 20px;">
                    @endif
                </div>
                <div class="col">
                    <table class="">
                        <tr>
                            <td width="250px">Количество образцов: </td>
                            <td>{{ $field->polygon->points->count() }}</td>
                        </tr>
                        <tr>
                            <td>Дата проведения АХО: </td>
                            <td>{{ $date }}</td>
                        </tr>
                        <tr>
                            <td>Исполнитель: </td>
                            <td>{{ $specialist }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>


    </div>

</body>


</html>