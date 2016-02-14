<?php

use Illuminate\Database\Seeder;

class EventsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
		DB::statement("SET FOREIGN_KEY_CHECKS = 0");
		DB::table('events')->truncate();

		DB::table('events')->insert([
			[
				"name" => "First Event",
				"bustime" => "2016-02-16 01:00:00",
				"description" => "This is my first event!",
				"user_id" => 1
			],
			[
				"name" => "Second Event",
				"bustime" => "2016-02-16 02:00:00",
				"description" => "This is my second event!",
				"user_id" => 1
			],
			[
				"name" => "Third Event",
				"bustime" => "2016-02-16 03:00:00",
				"description" => "This is my third event!",
				"user_id" => 3
			],
			[
				"name" => "Fourth Event",
				"bustime" => "2016-02-16 04:00:00",
				"description" => "This is my fourth event!",
				"user_id" => 1
			],
			[
				"name" => "Fifth Event",
				"bustime" => "2016-02-16 05:00:00",
				"description" => "This is my fifth event!",
				"user_id" => 3
			],
			[
				"name" => "Sixth Event",
				"bustime" => "2016-02-16 06:00:00",
				"description" => "This is my sixth event!",
				"user_id" => 1
			],
			[
				"name" => "Seventh Event",
				"bustime" => "2016-02-16 07:00:00",
				"description" => "This is my seventh event!",
				"user_id" => 3
			],
			[
				"name" => "Eighth Event",
				"bustime" => "2016-02-16 08:00:00",
				"description" => "This is my eighth event!",
				"user_id" => 4
			],
			[
				"name" => "Ninth Event",
				"bustime" => "2016-02-16 09:00:00",
				"description" => "This is my ninth event!",
				"user_id" => 1
			],
			[
				"name" => "Tenth Event",
				"bustime" => "2016-02-16 10:00:00",
				"description" => "This is my first event!",
				"user_id" => 3
			],
		]);

		foreach(App\Events::all() as $event) {
			$event->touch();
			$event->created_at = $event->updated_at;
			$event->save();
		}

		DB::statement("SET FOREIGN_KEY_CHECKS = 1");
    }
}
