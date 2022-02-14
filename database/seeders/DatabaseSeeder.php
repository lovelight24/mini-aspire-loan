<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        User::create([
                'name' => "Admin",
                'email' => "premmalviya02897+admin@gmail.com",
                'password' => bcrypt("12345678"),
                'type' => '1',
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
        ]);
        
        User::create([
                'name' => "borrower-1",
                'email' => "premmalviya02897+borrower1@gmail.com",
                'password' => bcrypt("12345678"),
                'type' => '2',
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
        ]);        
    }

}
