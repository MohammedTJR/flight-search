<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('search_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('origin', 3);
            $table->string('destination', 3);
            $table->date('departure_date');
            $table->integer('adults')->default(1);
            $table->integer('children')->default(0);
            $table->integer('infants_in_seat')->default(0);
            $table->integer('infants_on_lap')->default(0);
            $table->integer('travel_class')->default(1);
            $table->boolean('stops')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('search_history');
    }
};
