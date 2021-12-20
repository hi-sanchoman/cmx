<html>
    <body style="">
        <img src="{{ asset('img/qrcodes/qrcode_' . $qrcode->id . '.svg') }}" alt="">
        <br>
        <p style="text-align:center; width: 512px;">
            Метка №{{ $qrcode->point->num }}
            <br>
            (Поле №{{ $qrcode->point->polygon->field->num }}, {{ $qrcode->point->polygon->field->client->khname }})
            <br><br>
            Дата отбора: ____________________________
        </p>
    </body>
</html>