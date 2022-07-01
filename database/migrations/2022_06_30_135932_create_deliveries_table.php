<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('request_id');
            $table->foreign('request_id')->references('id')
                    ->on('requests');
            $table->unsignedInteger('senders_id');
            $table->foreign('senders_id')->references('id')
                    ->on('users');
            $table->string('comments')->nullable();//this is the comment/message
            $table->json('uploads_materials')->nullable();

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
        Schema::dropIfExists('deliveries');
    }
}