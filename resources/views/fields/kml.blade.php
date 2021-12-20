<kml xmlns="http://www.opengis.net/kml/2.2">
    <Placemark>
        <name>Field</name>
        <Polygon>
            <extrude>1</extrude>
            <outerBoundaryIs>
                <LinearRing>
                    <coordinates>
                    @foreach ($coordinates as $point)
                        {{ $point[0] }},{{ $point[1] }}, 100
                    @endforeach
                    </coordinates>
                </LinearRing>
            </outerBoundaryIs>
        </Polygon>
    </Placemark>

    @if (count($points) > 0)

        @foreach ($points as $point)
            <Placemark>
                <name>Метка {{ $point->num }}</name>
                <description></description>
                <Style>
                    <LabelStyle>
                        <color>A600FFFF</color>
                        <scale>1</scale>
                    </LabelStyle>
                </Style>
                <Point>
                    <extrude>1</extrude>
                    <coordinates>{{ $point->lon }},{{ $point->lat }},0 </coordinates>
                </Point>
            </Placemark>
        @endforeach

    @endif
</kml>