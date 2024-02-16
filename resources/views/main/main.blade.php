<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PAUT</title>

    <link rel="stylesheet" href="{{ asset('/leaflet/leaflet.css') }}" />
    
    
     
</head>
<body>
    <div id="map" ></div>

<script src="{{ asset('/leaflet/leaflet.js') }}"></script>
<script>
    let map = L.map('map').setView([8.2757,-62.7594],13);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

</script>
</body>
<style>
    #map{
        height: 1080px;
        width: 1920px;
    }    
</style>
</html>