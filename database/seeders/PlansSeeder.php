<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class PlansSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('plans')->insert([
            'plan_name' => 'Starter',
            'slug'=>strtolower('Starter'),
            'price' => '56500',
            'stripe_plan' =>'Starter',
            'stripe_price_id'=>'price_1L8jrvEL7CF34u9QNMw5Eo5R'
        ]);
        DB::table('plans')->insert([
            'plan_name' => 'Growth',
            'slug'=>strtolower('Growth'),
            'price' => '129500',
            'stripe_plan' =>'Growth',
            'stripe_price_id'=>'price_1L8jt5EL7CF34u9QGpMK8RCB'
        ]);
        DB::table('plans')->insert([
            'plan_name' => 'Scale',
            'slug'=>strtolower('Scale'),
            'price' => '249500',
            'stripe_plan' =>'Scale',
            'stripe_price_id'=>'price_1L8jtVEL7CF34u9Qf2Avapok'
        ]);
    }
}