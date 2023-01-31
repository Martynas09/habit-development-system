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
        Schema::create('reflection_answers', function (Blueprint $table) {
            $table->id();
            $table->string('content');
            $table->boolean('answer');
            $table->foreignId('fk_user')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('fk_question')->references('id')->on('reflection_questions')->onDelete('cascade');
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
        Schema::dropIfExists('reflection_answers');
    }
};
