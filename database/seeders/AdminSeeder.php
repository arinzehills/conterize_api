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
        \App\Models\User::create([
            'firstname' => 'Napolean',
            'lastname' => 'Okugbe',
            'email' => 'support@conterize.com',
            'password'=>bcrypt('123'),
            'user_type' => 'admin',
            'role_type' => 'admin',
        ]);
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
        \App\Models\User::create([
            'firstname' => 'achills',
            'lastname' => 'Chris',
            'email' => 'developer@conterize.com',
            'password'=>bcrypt('123'),
            'user_type' => 'admin',
            'role_type' => 'developer',
        ]);
        \App\Models\UserCredits::create(['user_id'=>'1']);
        \App\Models\UserCredits::create(['user_id'=>'2']);
        \App\Models\UserCredits::create(['user_id'=>'3']);
        \App\Models\UserCredits::create(['user_id'=>'4']);
    }
}