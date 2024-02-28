<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PAUT</title>

    <!-- <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
     integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
     crossorigin=""/> -->
      <!-- Make sure you put this AFTER Leaflet's CSS -->
 <!-- <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
     integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
     crossorigin=""></script> -->
    
     <link rel="stylesheet" href="{{ asset('/leaflet/leaflet.css') }}" />
    <script src="{{ asset('/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('/leaflet/leaflet.js') }}"></script>
         
</head>
<body>
<div class="container">
        <!-- MENU -->
        <div class="left">

            <div class="logo">
                <!-- <button id="btn3">Logo</button>   -->
                <img id="logo" src="2024.png">
            </div>

            <div class="button-container">
                <button class="reset-button" onclick="clearInitialStop()">
                    <h5>X</h5>
                </button>

                <button id="initialStopBTN" onclick="clickInitialStopBTN()">
                    <h5 id="initialStopName">Seleccionar Parada Inicial</h5>
                </button>
                
            </div>

            <div class="button-container ">
                <button class="reset-button" onclick="clearFinalStop()">
                    <h5>X</h5>
                </button>

                <button id="finalStopBTN" onclick="clickFinalStopBTN()">
                    <h5 id="finalStopName">Seleccionar Parada Final</h5>
                </button>
                
            </div>
            

            <div class="container-clear-calculate">
                <div style="text-align: center;" class="button-clear">
                    <button id="clearBTN" onclick="clearAll()">Limpiar</button>
                    <!-- <button onclick="">limpiar informacion</button> -->
                    
                </div>
                <div style="text-align: center;" class="button-calculate">
                    <button id="calculateBTN" onclick="calculateOptimalRoute()">Calcular</button>
                    <!-- <button onclick="">buscar</button> -->

                </div>
            </div>
        </div>

        <!-- MAP -->
        <div id="map" class="right" ></div>
       
    </div>
  


<script>

    /**
     * GLOBAL VARIABLES
     */
    var map = L.map('map').setView([8.258907987654922,-62.77459947679567],13);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
       attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);
    
    //FOR AJAX REQUEST
    var response;

    //FOR RENDERING

    var greenMarker = L.icon({
        iconUrl: "{{ asset('/green-marker.png') }}",
        iconSize: [38, 48],
       // shadowUrl: "{{ asset('/leafelt/images/marker-shadow.png') }}",
    });

    var redMarker = L.icon({
        iconUrl: "{{ asset('/red-marker.png') }}",
        iconSize: [48, 48],
       // shadowUrl: "{{ asset('/leafelt/images/marker-shadow.png') }}",
    });

    var defaultMarker = L.icon({
        iconUrl: "{{ asset('/leafelt/images/marker-icon.png') }}",
       // shadowUrl: "{{ asset('/leafelt/images/marker-shadow.png') }}",
    })

    var stops = [];
    var paths = [];

    var calculatedRoute = [];
    var calculatedPath = [];
    var pathPolyline = null;
    
    //FOR ROUTE CALCULATION
    var initialStop = null;
    var initialStopFlag = false;

    var finalStop = null;
    var finalStopFlag = false;


    // var pathCoordinates = [];
    // var path = L.polyline(pathCoordinates, {color: 'red'}).addTo(map);


    /**
     * MAIN EXECUTION
     */

    renderAllStops();

    //renderAllPaths();

    

    /**
     * FUNCTIONS
     */

    function renderAllStops() {
        ajax( '{{config('app.url')}}'+'/api/stop' ,'GET',function callback(response) {
            this.stops = response;    
        });
        
        this.stops.forEach( function(item, index, arr){
            item.marker = L.marker(item.location.coordinates.reverse()).addTo(this.map).bindPopup(item.name).bindTooltip(item.name);
            item.marker.stop_array_id = index;
            item.marker.addEventListener('click', e => {

                if(this.initialStopFlag == true){
                    this.initialStop =  arr[e.target.stop_array_id]
                    document.getElementById("initialStopName").innerHTML = this.initialStop.name;
                    e.target.setIcon(this.greenMarker);
                    console.log(this.initialStop);
                }

                if(this.finalStopFlag == true){
                    this.finalStop =  arr[e.target.stop_array_id]
                    document.getElementById("finalStopName").innerHTML = this.finalStop.name;
                    e.target.setIcon(this.redMarker);
                    console.log(this.finalStop);
                }               
            })
            arr[index] = item;

        });
         console.log(this.stops[0]);
    }


    function clickInitialStopBTN(e){
        if (this.initialStop != null) {
            this.initialStop.setIcon(this.defaultMarker);
        }
        this.initialStopFlag = true;
        this.finalStopFlag = false;
    }


    function clickFinalStopBTN(e){
        if (this.finalStop != null) {
            this.finalStop.setIcon(this.defaultMarker);
        }
        this.finalStopFlag = true;
        this.initialStopFlag = false;
    }


    function renderAllPaths() {
        ajax( '{{config('app.url')}}'+'/api/path' ,'GET',function callback(response) {
            this.paths = response;    
        });
        

        paths.forEach(function(pathItem,pathIndex,pathArr){
            pathItem.polyline = null; 
            // pathItem.coordinates.coordinates.forEach(function(coordItem, coordIndex, coordArr){
            //     coordArr[coordIndex] = coordArr[coordIndex].reverse()
            // })

            pathItem.polyline = L.polyline(pathItem.coordinates.coordinates.reverse(), {color: 'red'}).addTo(this.map);
        })
  
    }

    function  calculateOptimalRoute(){
        this.calculatedPath
        if(this.pathPolyline != null){
            this.map.removeLayer(this.pathPolyline);
        }
        this.pathPolyline = null;

        ajax( '{{config('app.url')}}'+'/api/calculate/route/'+this.initialStop.stop_id+'/'+this.finalStop.stop_id ,'GET',function callback(response) {
            this.calculatedRoute = response;    
        });

        this.calculatedRoute.route.forEach(function(routeItem, routeIndex, routeArr){
            this.calculatedPath.push([routeItem.stop[0].location.coordinates[1],routeItem.stop[0].location.coordinates[0]]);

        })

        this.pathPolyline = L.polyline(this.calculatedPath, {color: 'red'}).addTo(this.map);   
        
    }

    function clearInitialStop(){
        this.initialStopFlag = false;
        if (this.initialStop != null) {
            this.initialStop.setIcon(this.defaultMarker);
        }
        this.initialStop = null;
        document.getElementById("initialStopName").innerHTML = "Seleccionar Parada Inicial";
    }

    function clearFinalStop(){
        this.finalStopFlag = false;
        if (this.finalStop != null) {
            this.finalStop.setIcon(this.defaultMarker);
        }
        this.finalStop = null;
        document.getElementById("finalStopName").innerHTML = "Seleccionar Parada Final";
    }

    function clearAll(){
        this.initialStopFlag = false;
        if (this.initialStop != null) {
            this.initialStop.setIcon(this.defaultMarker);
        }
        this.initialStop = null;
        document.getElementById("initialStopName").innerHTML = "Seleccionar Parada Inicial";

        this.finalStopFlag = false;
        if (this.finalStop != null) {
            this.finalStop.setIcon(this.defaultMarker);
        }
        this.finalStop = null;
        document.getElementById("finalStopName").innerHTML = "Seleccionar Parada Final";

        this.calculatedRoute = [];
        this.calculatedPath = [];

        if(this.pathPolyline != null){
            this.map.removeLayer(this.pathPolyline);
        }

        this.pathPolyline = null;
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


    function onMapClick(e) {
        // Agregar un marcador al mapa en la ubicación del clic
        var marker = L.marker(e.latlng).addTo(map);

        // Agregar la ubicación del clic a la línea del camino
        pathCoordinates.push(e.latlng);
        path.setLatLngs(pathCoordinates);
    }

    // Agregar el manejador del evento de clic al mapa
    //map.on('click', onMapClick);


     //console.log(paths);

        //L.polyline(pathCoordinates, {color: 'red'}).addTo(map);
        
        //stops.forEach( function(item, index, arr){
            //item.marker = L.marker([item.location.coordinates[1],item.location.coordinates[0]]).addTo(map).bindPopup(item.name).bindTooltip(item.name);
            //arr[index] = item;

        //});

    

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
            display: flex; 
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
        #initialStopBTN {
            background-color: #82b1ec;
        }
        #finalStopBTN {
            background-color: #82b1ec;
        }

        .logo{
            position: relative;
            margin-bottom: 25%;

        }
        #logo {
            
            width: 100px;
            height: 100px;
            /* top: 0%; */
            /* left: 0%; */
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
        
        #calculateBTN {
            
            background-color: #bedf2e;
            height: 90%; /* Altura del botón  */
            width: 90%;
            border-radius:50%;
        }
        #clearBTN {
            background-color: red ;
            height: 90%; /* Altura del botón  */
            width:90%;
            border-radius:50%50%;     
        }
        /* Botón de reinicio */
        .reset-button {
            
           
            font-size: 20px; /* Tamaño de fuente más pequeño */
            background-color: black; 
            flex: 20%;
        }   
</style>
</html>