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
        \App\Models\User::create([
            'firstname' => 'Napolean',
            'lastname' => 'Okugbe',
            'email' => 'admin@conterize.com',
            'password'=>bcrypt('123'),
            'user_type' => 'admin',
            'role_type' => 'admin',
        ]);
        \App\Models\User::create([
            'firstname' => 'James',
            'lastname' => 'James',
            'email' => 'bravediseo@gmail.com',
            'password'=>bcrypt('123'),
            'user_type' => 'business_user',
            'payment_status'=>'paid',
        ]);
        // \App\Models\User::table('plans')->insert([
        //     'firstname' => 'Napolean',
        //     'lastname' => 'Okugbe',
        //     'email' => 'support@conterize.com',
        //     'password'=>strtolower('Scale'),
        //     'user_type' => 'admin',
        //     'role_type' => 'admin',
        // ]);
    }
}