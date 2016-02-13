<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Response;

class DefaultController extends Controller
{
    //
    public function getHeartbeat(Request $request) {
        return Response::json([
            "response" => "OK"
        ], 200);
    }
}
