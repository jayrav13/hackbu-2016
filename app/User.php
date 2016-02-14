<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'admin_flag', 'qr_code'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

	public function events() {
		return $this->hasMany('App\Events', 'user_id', 'id');
	}

	public function registered() {
		return $this->belongsToMany('App\Events', 'user_events', 'user_id', 'event_id')->withPivot('has_checked_in');
	}
}
