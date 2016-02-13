<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserEvents extends Model
{
    //
	protected $table = 'user_events';
	protected $fillable = [
		'user_id', 'event_id'
	];
	
}
