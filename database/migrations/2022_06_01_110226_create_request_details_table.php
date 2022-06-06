<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')
                    ->on('users');
            $table->unsignedInteger('request_id');
            $table->foreign('request_id')->references('id')
                    ->on('requests');
            $table->string('request_name')->nullable();
            $table->string('request_type')->nullable();
            $table->string('category')->nullable();
            $table->string('quantity')->nullable(); //this is for only graphics or content writing
            $table->string('size')->nullable(); //dimension, video length or word count
            $table->json('reference_links')->nullable();
            $table->string('description')->nullable();//active, archived or underreview
            $table->string('writing_topics')->nullable();
            $table->string('supporting_info')->nullable();
            $table->json('supporting_materials')->nullable();
            $table->string('video_format')->nullable();//this is for only video request
            $table->string('overview')->nullable();//this is for only video request
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
        Schema::dropIfExists('request_details');
    }
}