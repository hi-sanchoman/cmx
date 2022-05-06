<?xml version="1.0" encoding="UTF-8"?>
<kml xmlns="http://earth.google.com/kml/2.2">
    <Document>
        <Folder>
            <name>Path</name>
            <open>1</open>
            <Style>
                <ListStyle>
                    <listItemType>check</listItemType>
                    <bgColor>00ffffff</bgColor>
                </ListStyle>
            </Style>
            <Placemark>
                <name>Path {{ $path->id }}</name>
                <description></description>
                <Style>
                    <LineStyle>
                        <color>A6FFFF00</color>
                        <width>2</width>
                    </LineStyle>
                </Style>
                <LineString>
                    <extrude>1</extrude>
                    <coordinates>{{ $totalPath }}</coordinates>
                </LineString>
            </Placemark>
        </Folder>
    </Document>
</kml>