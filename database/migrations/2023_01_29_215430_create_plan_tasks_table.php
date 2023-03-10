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
        Schema::create('plan_tasks', function (Blueprint $table) {
            $table->id();
            $table->dateTime('execution_date');
            $table->boolean('is_done');
            $table->foreignId('fk_reminder')->references('id')->on('reminders')->onDelete('cascade');
            $table->foreignId('fk_task')->references('id')->on('tasks')->onDelete('cascade');
            $table->foreignId('fk_plan')->references('id')->on('plans')->onDelete('cascade');
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
        Schema::dropIfExists('plan_tasks');
    }
};
