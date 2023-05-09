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
        Schema::create('challenged_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fk_user')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('fk_challenge')->references('id')->on('challenges')->onDelete('cascade');
            $table->enum('status', ['pending', 'accepted', 'rejected','completed']);
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
        Schema::dropIfExists('challenged_users');
    }
};
