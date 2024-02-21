<?php

namespace App\Http\Controllers;

use MatanYadaev\EloquentSpatial\Objects\Point;
use App\Models\Path;


class PathController extends Controller
{
        public function read(){

            try {
                $paths = Path::all();

                $response = [   
                    'data'    => $paths,
                    'message' => "All paths",
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

        public function readById($path_id){

            try {
                $path = Path::where('path_id',$path_id)->first();
                    if(!$path){

                        $response = [   
                            'data'    => null,
                            'message' => "path not found ",
                            'code' =>400
                        ];
                        return response()->json($response, 200);
                    }
                
                $response = [   
                    'data'    => $path,
                    'message' => " path id:$path->path_id",
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
