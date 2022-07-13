<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ContentCreators extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('content_creators', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')
            ->on('users');
            $table->string('total_completed')->default('0')->nullable();
            $table->string('activated')->default('yes')->nullable();//no or yes
            $table->string('ongoing_projects')->default('0')->nullable();
            $table->string('niche')->nullable();
            //either main video creation,content writing, or graphics design
            // $table->string('role_type')->nullable();
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
        //
        Schema::dropIfExists('content_creators');
    }
}