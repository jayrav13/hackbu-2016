<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
		DB::table('users')->insert([
				[
					"name" => "Jay Ravaliya",
					"email" => "jay@gmail.com",
					"password" => md5("password"),
					"remember_token" => md5("password" . time())
				],
				[
					"name" => "Varun Shah",
					"email" => "varun@gmail.com",
					"password" => md5("password"),
					"remember_token" => md5("password" . time())
				],
				[
					"name" => "Saurabh Palaspagar",
					"email" => "saurabh@gmail.com",
					"password" => md5("password"),
					"remember_token" => md5("password" . time())
				],
				[
					"name" => "Pranav Patel",
					"email" => "pranav@gmail.com",
					"password" => md5("password"),
					"remember_token" => md5("password" . time())
				],
		]);

		DB::table('users')->insert([
				[
					"name" => "Super User",
					"email" => "super@gmail.com",
					"password" => md5("password"),
					"remember_token" => md5("password" . time()),
					"admin_flag" => 1
				]
	
		]);

		foreach(App\User::all() as $user) {
			$user->touch();
			$user->created_at = $user->updated_at;
			$user->save();
		}

    }
}
