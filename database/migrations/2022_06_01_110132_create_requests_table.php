<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')
                    ->on('users');
            $table->string('request_name')->nullable();
            $table->string('category')->nullable();
            $table->string('assign_to')->nullable();
            $table->string('status')->nullable();//active, archived or underreview
            $table->string('is_draft')->nullable();//active, archived or underreview
            $table->string('submitted_by')->nullable();//which customer made the request
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
        Schema::dropIfExists('requests');
    }
}