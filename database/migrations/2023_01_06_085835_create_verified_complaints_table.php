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
        Schema::create('verified_complaints', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('assigned_to_department_id')->nullable();
            $table->string('common_title');
            $table->longText('description')->nullable();
            $table->unsignedBigInteger('status_id');
            $table->unsignedBigInteger('complaint_action_id');
            $table->longText('finalize_remark')->nullable();
            $table->timestamps();

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
        Schema::dropIfExists('verified_complaints');
    }
};
