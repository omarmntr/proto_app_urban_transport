<?php

namespace App\Http\Controllers;

use MatanYadaev\EloquentSpatial\Objects\Point;
use App\Models\Stop;


class StopController extends Controller
{
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
            return response()->json($response, 200);
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
