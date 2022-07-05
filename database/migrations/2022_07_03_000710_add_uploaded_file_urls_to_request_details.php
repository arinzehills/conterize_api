<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUploadedFileUrlsToRequestDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('request_details', function (Blueprint $table) {
            //
            $table->json('uploaded_file_urls')->after('supporting_materials')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('request_details', function (Blueprint $table) {
            //
            $table->dropColumn([
                'uploaded_file_urls'
             ]);
        });
    }
}