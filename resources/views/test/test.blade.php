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
    }
    
    .container {
        /*width: 100%;*/
        margin: 30px;
        border:  4px solid green;
    }

    .header {
        height: 200px;
        border:  4px solid green;
    }

    .footer {
        height: 100px;
        border: 4px solid green;
    }


    h1 {
        color: #0000FF;
        font-size: 18px;
        display: inline-block;
        margin-top: 20px;
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
                    <h1>Картограмма содержания подвижного калия</h1>
                </div>
            </div>

            <div style="clear:both;"></div>

            <div class="row" style="margin-top: 15px;">
                <table class="" style="margin-left: 40px;">
                    <tr>
                        <td width="200px">Заказчик: </td>
                        <td>КХ "Кызылшокы"</td>
                    </tr>
                    <tr>
                        <td>Населенный пункт: </td>
                        <td>Алматинская область, Керуенский район</td>
                    </tr>
                    <tr>
                        <td>Поле</td>
                        <td>34</td>
                    </tr>
                    <tr>
                        <td>Площадь: </td>
                        <td>34,00</td>
                    </tr>
                    <tr>
                        <td>Лаборатория: </td>
                        <td>ТОО "CemEX Engineering"</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="content" style="padding: 100px;">
            
            <h3>Здесь будет картограмма</h3>

        </div>

        <div class="footer" style="padding-top: 30px;">
            <div class="row">
                <div class="col">
                    Legend
                </div>
                <div class="col">
                    <table class="">
                        <tr>
                            <td width="250px">Количество образцов: </td>
                            <td>1</td>
                        </tr>
                        <tr>
                            <td>Дата проведения АХО: </td>
                            <td>23.09.2021</td>
                        </tr>
                        <tr>
                            <td>Исполнитель: </td>
                            <td>Баймуратов А.Ш.</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>


    </div>

</body>


</html>