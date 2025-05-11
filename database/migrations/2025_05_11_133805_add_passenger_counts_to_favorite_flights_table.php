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
        Schema::table('favorite_flights', function (Blueprint $table) {
            $table->unsignedInteger('adults')->default(1)->after('search_params');
            $table->unsignedInteger('children')->default(0)->after('adults');
            $table->unsignedInteger('infants_in_seat')->default(0)->after('children');
            $table->unsignedInteger('infants_on_lap')->default(0)->after('infants_in_seat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('favorite_flights', function (Blueprint $table) {
            $table->dropColumn(['adults', 'children', 'infants_in_seat', 'infants_on_lap']);
        });
    }
};
