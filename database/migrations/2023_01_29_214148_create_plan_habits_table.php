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
        Schema::create('plan_habits', function (Blueprint $table) {
            $table->foreignId('fk_plan')->references('id')->on('plans')->onDelete('cascade');
            $table->foreignId('fk_habit')->references('id')->on('habits')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plan_habits');
    }
};
