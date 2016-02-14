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
			"response" => Events::with('admin')->with('attendees')->get(),
			"user" => User::where('remember_token', $request->token)->first()
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
		$user->events()->save($event);
		$user_event = App\UserEvent::create([
			"user_id" => $user->id,
			"event_id" => $event->id,
			"has_checked_in" => 1
		]);
		return Response::json([
			"status" => "OK",
			"response" => $event,
			"user" => $user
		], 200)->header('Access-Control-Allowed-Origin', '*');
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
	public function eventAttendees(Request $request, $event_id) {
		return Response::json([
			"status" => "OK",
			"response" => Events::where('id', $event_id)->with('attendees')->first()
		], 200);
	}

	public function deregisterUser(Request $request, $event_id) {
		$user = User::where('remember_token', $request->token)->first();
		$user_event = UserEvents::where('user_id', $user->id)->where('event_id', $event_id)->first();
		if($user_event) {
			$user_event->delete();
			return Response::json([
				"status" => "OK",
				"response" => "Deregistered from this event!"
			], 200);
		}
		else {
			return Response::json([
				"status" => "NOT_OK",
				"response" => "You were not registered for this event."
			], 400);
		}
	}

	public function deleteEvent(Request $request, $event_id) {
		$event = Events::find($event_id);
		$user = User::where('remember_token', $request->token)->first();
		if($event->admin->id != $user->id) {
			return Response::json([
				"status" => "NOT_OK",
				"response" => "Event can only be deleted by event creator"
			], 400);
		}

		// $event->delete();

		return Response::json([
			"status" => "OK",
			"response" => "Event deleted!"
		], 200);

	}

}
