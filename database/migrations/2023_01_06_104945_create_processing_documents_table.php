<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('processing_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('complaint_logging_id');
            $table->longText('file_path')->nullable();
            $table->longText('file_name')->nullable();
            $table->string('mime_type')->nullable();
            $table->timestamps();

            $table->foreign('complaint_logging_id')->references('id')->on('complaint_loggings');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('processing_documents');
    }
};
