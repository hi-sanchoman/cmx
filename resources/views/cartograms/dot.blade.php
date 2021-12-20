<html>

<head>
    <style>
    
    html, div, img, span {
        background-color: transparent;
    }

    </style>
</head>


<body style="background: none">
    <div style="background: none">
        <img src="{{ asset('img/map_dot.png') }}" alt="" style="margin: 0 auto; display: block;">
        <span style="text-align: center; display: block; width: 100%; font-size: 20px">{{ $results[$value][$pos] }}</span>
    </div>
</body>
</html>