<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Response;
use App\Events;
use App\User;
use App\UserEvents;
use Illuminate\Support\Facades\Input;

class EventsController extends Controller
{
    //
	public function listEvents(Request $request) {
		return Response::json([
			"status" => "OK",
			"response" => Events::all()
		], 200);
	}

	public function createEvent(Request $request) {
		if(!$request->name || !$request->bustime || !$request->description) {
			return Response::json([
				"status" => "NOT_OK",
				"response" => "Fields required: \"name\", \"bustime\", \"description\""
			], 400);
		}
		$user = User::where('remember_token', $request->token)->first();
		$event = new Events;
		$event->fill(Input::all());
		$user->admin()->save($event);
	}

	public function registerForEvent(Request $request) {
		if(!$request->event_id) {
			return Response::json([
				"status" => "NOT_OK",
				"response" => "Fields required: \"event_id\""
			]);
		}
	
		$event = Events::where('id', $request->event_id)->first();
		if(!$event) {
			return Response::json([
				"status" => "NOT_OK",
				"response" => "Event doesn't exist!"
			], 400);
		}
		$user = User::where('remember_token', $request->token)->first();
		UserEvents::create([
			"user_id" => $user->id, "event_id" => $event->id
		]);	

		return Response::json([
			"status" => "OK",
			"response" => "User registered for event!"	
		], 200);
	}

	

}
