<?php

use Illuminate\Database\Seeder;
use App\Ttl;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);

        DB::statement("SET foreign_key_checks=0");
        Ttl::truncate();
        DB::statement("SET foreign_key_checks=1");

        Ttl::create(['ttl' => 5]); //in minutes
    }
}
