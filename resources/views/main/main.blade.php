<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PAUT</title>

    <link rel="stylesheet" href="{{ asset('/leaflet/leaflet.css') }}" />
         
</head>
<body>
<div class="container">
        <!-- MENU -->
        <div class="left">

            <div class="logo">
                <button id="btn3">Logo</button>  
            </div>

            <div class="button-container">
                <button id="btn1">Botón 1</button>
                <button class="reset-button" onclick="resetFunction1()">Limpiar</button>
            </div>
            <div class="button-container">
                <button id="btn2">Botón 2</button>
                <button class="reset-button" onclick="resetFunction2()">Limpiar</button>
            </div>
            

            <div class="container-clear-calculate">
                <div style="text-align: center;" class="button-clear">
                    <button id="btn4">limpiar </button>
                    <!-- <button onclick="">limpiar informacion</button> -->
                    
                </div>
                <div style="text-align: center;" class="button-calculate">
                    <button id="btn5">buscar</button>
                    <!-- <button onclick="">buscar</button> -->

                </div>
            </div>
        </div>

        <!-- MAP -->
        <div id="map" class="right" ></div>
       
    </div>
  

<script src="{{ asset('/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('/leaflet/leaflet.js') }}"></script>
<script>
    let map = L.map('map').setView([8.258907987654922,-62.77459947679567],13);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);
    
    //FOR AJAX REQUEST
    var response;

    var stops = [];
    var paths = [];


    var pathCoordinates = [];
    var path = L.polyline(pathCoordinates, {color: 'red'}).addTo(map);


    renderAllStops();

    renderAllPaths();

    function onMapClick(e) {
        // Agregar un marcador al mapa en la ubicación del clic
        var marker = L.marker(e.latlng).addTo(map);

        // Agregar la ubicación del clic a la línea del camino
        pathCoordinates.push(e.latlng);
        path.setLatLngs(pathCoordinates);
    }
    // Agregar el manejador del evento de clic al mapa
    //map.on('click', onMapClick);

    function resetFunction1() {
            // Aquí va el código para limpiar la función del Botón 1
        }
        function resetFunction2() {
            // Aquí va el código para limpiar la función del Botón 2
        }
        function resetFunction3() {
            // Aquí va el código para limpiar la función del Botón 3
        }

    function atStart() {
        
    }

    function renderAllStops() {
        ajax( '{{config('app.url')}}'+'/api/stop' ,'GET',function callback(response) {
            this.stops = response;    
        });
        
        stops.forEach( function(item, index, arr){
            item.marker = L.marker([item.location.coordinates[1],item.location.coordinates[0]]).addTo(map).bindPopup(item.name).bindTooltip(item.name);
            arr[index] = item;

        });
        
    }

    function renderAllPaths() {
        ajax( '{{config('app.url')}}'+'/api/path' ,'GET',function callback(response) {
            this.paths = response;    
        });
        //console.log(paths);

        paths.forEach(function(pathItem,pathIndex,pathArr){
            pathItem.polyline = null; 
            pathItem.coordinates.coordinates.forEach(function(coordItem, coordIndex, coordArr){
                coordArr[coordIndex] = coordArr[coordIndex].reverse()
            })

            pathItem.polyline = L.polyline(pathItem.coordinates.coordinates, {color: 'red'}).addTo(map);
        })

        console.log(paths);

        //L.polyline(pathCoordinates, {color: 'red'}).addTo(map);
        
        //stops.forEach( function(item, index, arr){
            //item.marker = L.marker([item.location.coordinates[1],item.location.coordinates[0]]).addTo(map).bindPopup(item.name).bindTooltip(item.name);
            //arr[index] = item;

        //});
        
    }

    function  calculateOptimalRoute(){

    }

    function renderoptimalRoute() {
        
    }

    function clearInfo(){

    }

    function selectFirstStop(){

    }

    function selectLastStop(){

    }

    function clearFirstStop(){

    }

    function clearLastStop(){
        
    }

    function ajax(url, type = 'GET',callback){
        
        $.ajax({
				 url:url,
    			 type:type,
                 async:false,
				 success: function(response) {
            // For example, filter the response
                callback(response.data);
        },
				 error: function(){
					 console.log("no fue posible completar la operacion");
				 }
			 }).done(function (response){
                return response.data;
             });
    }

</script>
</body>
<style>
     
    .container {
            display: flex;
            width: 100%;
            height:100%;
        }
        .left {
            flex: 30%;
            background-color: #f1f1f1;
            padding: 20px;
            height: 100%;
          
        }
        .right {
            width :400px; 
            flex: 70%;
            background-color: #ddd;
            padding: 20px;
        }
        #map{
            height: 100%;
            width: 100%;
        }  
        .button-container {
            position: relative;
            width: 100%;
            margin-bottom: 10px;
            height: 100px; 
            
        }
        button {
            padding: 15px 20px;
            font-size: 20px;
            color: white;
            width: 100%;
        }
        /* Colores de los botones principales */
        #btn1 {
            background-color: red;
        }
        #btn2 {
            background-color: green;
        }

        .logo{
            position: relative;
            width: 25%;
            height: 25%;
            padding-bottom: 10%;
        }
        #btn3 {
            background-color: blue;
            position: absolute;
            top: 0;
            left: 0;
        }

        .container-clear-calculate{
            display: flex;
        }
        .button-clear{
            flex: 30%;
        }
        .button-calculate{
            flex: 70%;
        }
        
        #btn4 {
            
            background-color: #f6ff33;
            height: 90%; /* Altura del botón  */
            width: 90%;
            
        }
        #btn5 {
            background-color: #33f6ff ;
            height: 90%; /* Altura del botón  */
            width:90%;
            
        }
        /* Botón de reinicio */
        .reset-button {
            position: absolute;
            top: 0;
            left: 0; /* Ubicación a la izquierda */
            padding: 2px; /* Tamaño más pequeño */
            font-size: 8px; /* Tamaño de fuente más pequeño */
            background-color: black;
            height: 20px; /* Altura del botón  */
            width: 20%; /* Ancho del botón  */
        }   
</style>
</html>