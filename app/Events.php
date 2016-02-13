<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Events extends Model
{
	protected $table = 'events';
	protected $fillable = [
		'name', 'bustime', 'description'
	];

	protected $guarded =[
		'created_at', 'updated_at', 'user_id', 'id'
	];

	public function admin() {
		return $this->hasOne('App\User');
	}

	public function attendees() {
		return $this->hasMany('App\User', 'user_events', 'event_id', 'user_id');
	}
}
