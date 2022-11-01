<?php

use App\Http\Controllers\ApiController;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::any('tracking-data', function (Request $request) {
    $location = new Location;
    $location->lat = $request->latitude;
    $location->lng = $request->longitude;
    $location->save();
});

Route::any('tracking-data-show', function (Request $request) {
    $locations = Location::latest()->select('lat','lng','created_at')->take(10)->get();
    return response()->json([
        'data'=> $locations
    ]);
});
