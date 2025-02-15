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
        Schema::create('character_items', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('picture');
            $table->enum('rarity', ['common', 'rare', 'uncommon', 'very rare']);
            $table->enum('category', ['head', 'top', 'bottom', 'shoes','accessory']);
            $table->foreignId('fk_level')->references('id')->on('levels')->onDelete('cascade');
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
        Schema::dropIfExists('character_items');
    }
};
