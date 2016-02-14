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

	public function registerForEvent(Request $request, $event_id) {
		if(!$event_id) {
			return Response::json([
				"status" => "NOT_OK",
				"response" => "Routes required: \"event_id\""
			]);
		}
	
		$event = Events::where('id', $event_id)->first();
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

	public function checkinUser(Request $request, $event_id) {
		if(!$event_id || !$request->rider_token) {
			return Response::json([
				"status" => "NOT_OK",
				"response" => "Fields required: \"event_id\", \"rider_token\""
			], 404);
		}
		$user = User::where('remember_token', $request->rider_token)->first();
		$user_event = UserEvents::where('user_id', $user->id)->where('event_id', $event_id)->first();
		if(!$user_event) {
			return Response::json([
				"status" => "NOT_OK",
				"response" => "User is not registered for this event."
			], 400);
		}
		$user_event->has_checked_in = 1;
		$user_event->save();
		return Response::json([
			"status" => "OK",
			"response" => "User has been checked in!"
		], 200);
	}
}
