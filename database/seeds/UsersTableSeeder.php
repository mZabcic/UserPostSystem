<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'first_name' => 'Mislav',
            'last_name' => 'Žabčić',
            'email' => 'mislav.zabcic@gmail.com',
            'password' => bcrypt('secret'),
            'date_of_birth' =>Carbon::createFromDate(1993, 5, 18),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        DB::table('users')->insert([
            'first_name' => 'Test',
            'last_name' => 'Testić',
            'email' => 'test@gmail.com',
            'password' => bcrypt('secret'),
            'date_of_birth' =>Carbon::createFromDate(1986, 1, 18),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        DB::table('users')->insert([
            'first_name' => 'Testko',
            'last_name' => 'Testković',
            'email' => 'testko@gmail.com',
            'password' => bcrypt('secret'),
            'date_of_birth' =>Carbon::createFromDate(1956, 12, 12),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}

