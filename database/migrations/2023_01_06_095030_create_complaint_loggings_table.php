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
        Schema::create('complaint_loggings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('verified_complaint_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('assigned_to_department_id')->nullable();
            $table->longText('remark')->nullable();
            $table->unsignedBigInteger('status_id');
            $table->unsignedBigInteger('complaint_action_id');
            $table->timestamps();

            $table->foreign('verified_complaint_id')->references('id')->on('verified_complaints');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('assigned_to_department_id')->references('id')->on('departments');
            $table->foreign('status_id')->references('id')->on('statuses');
            $table->foreign('complaint_action_id')->references('id')->on('complaint_actions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('complaint_loggings');
    }
};
