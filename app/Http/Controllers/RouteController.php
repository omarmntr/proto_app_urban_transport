<?php

namespace App\Http\Controllers;

use MatanYadaev\EloquentSpatial\Objects\Point;

use App\Models\Route;

use App\Models\Config;


use App\Models\Stop;
use App\Models\RouteStop;

use App\Models\Path;
use App\Models\RoutePath;


class RouteController extends Controller
{
        public function calculateRoute($initialStop, $finalStop){

            try {
                $time_between_stops = Config::where("name","time_between_stops")->first();
                $time_in_stop = Config::where("name","time_in_stop")->first();

                $time_total = (float) $time_between_stops->value + (float) $time_in_stop->value;
                
                $time_total_segmnt = null;
                
                $routeCalculated = null;

                $result_direct = 0.0; 

                $data = null;

                //Direct Route
                $directRoutes = RouteStop::whereIn('stop_id',[$initialStop,$finalStop]) 
                ->groupBy('route_id')
                ->with(["route"])
                ->get();
                
                
                foreach($directRoutes as $directRoute){

                    $stops = RouteStop::whereIn('stop_id',[$initialStop,$finalStop]) 
                    ->where("route_id",$directRoute->route_id)
                    ->orderBy("order_stop","asc")
                    ->get();

                    

                    $routeStopSegment = RouteStop::where("route_id",$directRoute->route_id)
                    ->where('order_stop','>=',$stops[0]->order_stop)
                    ->where('order_stop','<=',$stops[1]->order_stop)
                    ->with(['stop'])
                    ->get();

                    $num_segmt = count($routeStopSegment) - 1;
 
                    $time_total_segmnt = $num_segmt * $time_total;

                    if( $result_direct == 0.0){
                        $data = collect(["route"=>$routeStopSegment,
                                        "approximate_travel_time"=>$time_total_segmnt,
                                        "fares" => 1]);
                        

                        $result_direct = $time_total_segmnt;
                    }
                    
                    if( $result_direct > ($num_segmt * $time_total)){
                       $data = collect(["route"=>$routeStopSegment,
                                       "approximate_travel_time"=>$time_total_segmnt >= $result_direct,
                                       "fares" => 1]);

                        $result_direct = $time_total_segmnt;
                    }              
                }


                $response = [   
                    'data'    => $data,
                    'message' => "Calculated Route",
                    'code' =>200
                ];
                return response()->json($response, 200);

            } catch (\Exception $e) {
            $response = [   
                'error' => true,
                'message' => $e->getMessage(),
                'trace' =>$e->getTrace(),
                'line' => $e->getLine(),
            ];
            return response()->json($response, 500);
            }

        }


        public function read(){

            try {
                $stops = Stop::all();

                $response = [   
                    'data'    => $stops,
                    'message' => "All stops",
                    'code' =>200
                ];
            return response()->json($response, 200);
            } catch (\Exception $e) {
                $response = [   
                    'error' => true,
                    'message' => $e->getMessage,
                    'trace' =>$e->getTrace
                ];
            return response()->json($response, 500);
            }

            
        }

        public function readById($stop_id){

            try {
                $stop = Stop::where('stop_id',$stop_id)->first();
                    if(!$stop){

                        $response = [   
                            'data'    => null,
                            'message' => "Stop not found ",
                            'code' =>400
                        ];
                        return response()->json($response, 200);
                    }
                
                $response = [   
                    'data'    => $stop,
                    'message' => " stop id:$stop->stop_id - stop name:$stop->name ",
                    'code' =>200
                ];
                return response()->json($response, 200);
            } catch (\Exception $e) {
                $response = [   
                    'error' => true,
                    'message' => $e->getMessage,
                    'trace' =>$e->getTrace
                ];
            return response()->json($response, 500);
            }



        }
}
