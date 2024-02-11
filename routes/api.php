<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Saloon\XmlWrangler\XmlReader;
use App\Models\Stop;
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

Route::get('/test-1', function () {
    $xml = simplexml_load_file(__DIR__ . '\file.xml');
    dd($xml->tag[2]);
    foreach ($xml->node as $nodeIndex => $nodeValue) {
        //dd($nodeValue);
        if ($nodeValue->tag) {
            foreach ($nodeValue->tag as $tagIndex => $tagValue) {

            }
        }
    }

});
Route::get('/test-1', function () {

    $query = 'node
["amenity"~".*"]
(38.415938460513274,16.06338500976562,39.52205163048525,17.51220703125);
out;';

    $context = stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => ['Content-Type: application/x-www-form-urlencoded'],
            'content' => 'data=' . urlencode($query),
        ]
    ]);

    # please do not stress this service, this example is for demonstration purposes only.
    $endpoint = 'http://overpass-api.de/api/interpreter';
    libxml_set_streams_context($context);
    $start = microtime(true);

    $result = simplexml_load_file($endpoint);
    printf("Query returned %2\$d node(s) and took %1\$.5f seconds.\n\n", microtime(true) - $start, count($result->node));

    //
// 2.) Work with the XML Result
//

    # get all school nodes with xpath
    $xpath = '//node[tag[@k = "amenity" and @v = "school"]]';
    $schools = $result->xpath($xpath);
    printf("%d School(s) found:\n", count($schools));
    foreach ($schools as $index => $school) {
        # Get the name of the school (if any), again with xpath
        list($name) = $school->xpath('tag[@k = "name"]/@v') + ['(unnamed)'];
        printf("#%02d: ID:%' -10s  [%s,%s]  %s\n", $index, $school['id'], $school['lat'], $school['lon'], $name);
    }
});


Route::get('/test-2', function () {
    //try {
    $xml = simplexml_load_file(__DIR__.'\file.xml');

    foreach($xml->xpath('//node[tag[@k = "bus" and @v="yes"] | tag[@k="name"] | tag[@k="public_transport" and @v="stop_position"]]') AS $node){
        
        $stop = new Stop();
        $stop->location = new Point((float) $node['lat'][0], (float) $node['lon'][0]);
        $stop->name = (string) $node->xpath('tag[@k="name"]/@v')[0];
        
        $stop->save();

        return $stop->location->getCoordinates();
        
    }
    /*} catch (\Exception $e) {
        return $e->getTrace();
    }*/
});