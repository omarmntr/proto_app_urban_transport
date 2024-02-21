<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Stop as StopModel;
use App\Models\Route as RouteModel;
use App\Models\Path as PathModel;
use App\Models\RouteStop as RouteStopModel;
use App\Models\RoutePath as RoutePathModel;
use MatanYadaev\EloquentSpatial\Objects\Point;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('test', function () {
    try {
    $xml = simplexml_load_file(__DIR__.'\map.xml');

    $routes = $xml->xpath('//relation[ tag[@k = "public_transport:version" and @v="2"]]');
   
    foreach($routes as $routeIndex => $routeValue){
        $route = new RouteModel();

       //dd(array($routeIndex, $routeValue));

    //    dd($route->where("osm_id",'=',(string) $routeValue['id'][0]."")->first());

    //    if($route->where("osm_id",'=',(string) $routeValue['id'][0])->limit(1)){
    //     continue;
    //    }
        
        if($routeValue->xpath('tag[@k="official_name"]') ){

            

            $route->osm_id = (string) $routeValue['id'][0];
            $route->name = (string) $routeValue->xpath('tag[@k="official_name"]/@v')[0];
            $route->colour = (string) $routeValue->xpath('tag[@k="colour"]/@v')[0];  
            $route->save();


            $stops = $routeValue->xpath('member[@type="node"]');

            foreach ($stops as $stopIndex => $stopValue) {
                $stop = new StopModel();

                //search node in XML
                $node = $xml->xpath('node[@id="'.(string) $stopValue["ref"][0].'"]');
                
                $stop->osm_id = (string) $node[0]['id'][0];
                $stop->name = (string) $node[0]->xpath('tag[@k="name"]/@v')[0];
                $stop->location = new Point((float) $node[0]['lat'][0], (float) $node[0]['lon'][0]);
                $stop->save();
                

                $routeStop = new RouteStopModel();

                $routeStop->route_id = $route->route_id;
                $routeStop->stop_id = $stop->stop_id;
                $routeStop->order_stop = $stopIndex;
                $routeStop->save();

                // dd($routeStop);


            }

            // dd(array_reverse($stops));

            $paths = $routeValue->xpath('member[@type="way"]');

            foreach ($paths as $pathIndex => $pathValue) {
                
            }




        }else{
            continue;
            
        }

        //$route->save();
        
        // $stop = new Stop();
        // $stop->location = new Point((float) $node['lat'][0], (float) $node['lon'][0]);
        // $stop->name = (string) $node->xpath('tag[@k="name"]/@v')[0];
        
        //$stop->save();

        //return $stop->location->getCoordinates();
        
    }
    } catch (\Exception $e) {
        return $e->getTrace();
    }
});


//     $query = 'node
// ["amenity"~".*"]
// (38.415938460513274,16.06338500976562,39.52205163048525,17.51220703125);
// out;';

//     $context = stream_context_create([
//         'http' => [
//             'method' => 'POST',
//             'header' => ['Content-Type: application/x-www-form-urlencoded'],
//             'content' => 'data=' . urlencode($query),
//         ]
//     ]);

//     # please do not stress this service, this example is for demonstration purposes only.
//     $endpoint = 'http://overpass-api.de/api/interpreter';
//     libxml_set_streams_context($context);
//     $start = microtime(true);

//     $result = simplexml_load_file($endpoint);