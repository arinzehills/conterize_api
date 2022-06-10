<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->increments('id');
            $table->string('plan_name')->nullable();//same as stripe plan name
            $table->string('slug')->unique();//id of the table
            $table->string('stripe_price_id')->unique();//id on stripe dashboard
            $table->string('stripe_plan')->nullable();
            $table->float('price')->nullable();//used if is to be displayed on UI
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plans');
    }
}