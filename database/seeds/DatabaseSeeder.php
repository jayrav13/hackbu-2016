<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		Eloquent::unguard();

        $this->call(UserTableSeeder::class);
		$tbis->call(EventsTableSeeder::class);

		Eloquent::reguard();
    }
}
