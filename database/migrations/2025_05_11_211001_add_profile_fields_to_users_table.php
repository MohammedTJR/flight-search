<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('gender', 20)->nullable()->after('avatar');
            $table->string('country', 2)->nullable()->after('gender');
            $table->string('currency', 3)->nullable()->after('country');
            $table->string('language', 10)->nullable()->after('currency');
            $table->string('phone', 20)->nullable()->after('language');
            $table->date('birth_date')->nullable()->after('phone');
            $table->string('address', 255)->nullable()->after('birth_date');
            $table->json('notification_preferences')->nullable()->after('address');
            $table->json('travel_preferences')->nullable()->after('notification_preferences');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'gender',
                'country',
                'currency',
                'language',
                'phone',
                'birth_date',
                'address',
                'notification_preferences',
                'travel_preferences'
            ]);
        });
    }
};