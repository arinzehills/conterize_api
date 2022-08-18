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
        // DB::table('plans')->insert([
        //     'plan_name' => 'Starter',
        //     'slug'=>strtolower('Starter'),
        //     'price' => '56500',
        //     'stripe_plan' =>'Starter',
        //     'stripe_price_id'=>'price_1LLcqMHbHAd0T9U9TdbyCgih'//napoleons
        //     // 'stripe_price_id'=>'price_1L8jrvEL7CF34u9QNMw5Eo5R' //mine
        // ]);
        // DB::table('plans')->insert([
        //     'plan_name' => 'Growth',
        //     'slug'=>strtolower('Growth'),
        //     'price' => '129500',
        //     'stripe_plan' =>'Growth',
        //     'stripe_price_id'=>'price_1LLcs0HbHAd0T9U90CRPuzb5'//for life
        //     // 'stripe_price_id'=>'price_1LMeLlHbHAd0T9U9U2TUYqsJ'for test
        //     // 'stripe_price_id'=>'price_1L8jt5EL7CF34u9QGpMK8RCB'//mine
        // ]);
        // DB::table('plans')->insert([
        //     'plan_name' => 'Scale',
        //     'slug'=>strtolower('Scale'),
        //     'price' => '249500',
        //     'stripe_plan' =>'Scale',
        //     'stripe_price_id'=>'price_1LLctAHbHAd0T9U9hGlHiq1J'//for life
        //     // 'stripe_price_id'=>'price_1LMeM8HbHAd0T9U9dgo8xU9x'//for test
        //     // 'stripe_price_id'=>'price_1L8jtVEL7CF34u9Qf2Avapok'//mine
        // ]);
        DB::table('plans')->insert([
            'plan_name' => 'Test',
            'slug'=>strtolower('Test'),
            'price' => '10',
            'stripe_plan' =>'Scale',
            'stripe_price_id'=>'price_1LY6MhHbHAd0T9U9fDdfCEcy'//for life
            // 'stripe_price_id'=>'price_1LMeM8HbHAd0T9U9dgo8xU9x'//for test
            // 'stripe_price_id'=>'price_1L8jtVEL7CF34u9Qf2Avapok'//mine
        ]);
    }
}