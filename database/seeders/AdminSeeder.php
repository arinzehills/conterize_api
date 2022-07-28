<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        // \App\Models\User::create([
        //     'firstname' => 'Napolean',
        //     'lastname' => 'Okugbe',
        //     'email' => 'support@conterize.com',
        //     'password'=>bcrypt('123'),
        //     'user_type' => 'admin',
        //     'role_type' => 'admin',
        // ]);
        // \App\Models\User::create([
        //     'firstname' => 'Napolean',
        //     'lastname' => 'Okugbe',
        //     'email' => 'admin@conterize.com',
        //     'password'=>bcrypt('123'),
        //     'user_type' => 'admin',
        //     'role_type' => 'admin',
        // ]);
         \App\Models\User::create([
            'firstname' => 'achills',
            'lastname' => 'Chris',
            'email' => 'developer@conterize.com',
            'password'=>strtolower('Scale'),
            'user_type' => 'admin',
            'role_type' => 'developer',
        ]);
        
        // \App\Models\User::table('plans')->insert([
        //     'firstname' => 'achills',
        //     'lastname' => 'tech',
        //     'email' => 'developer@conterize.com',
        //     'password'=>strtolower('Scale'),
        //     'user_type' => 'admin',
        //     'role_type' => 'developer',
        // ]);
    }
}