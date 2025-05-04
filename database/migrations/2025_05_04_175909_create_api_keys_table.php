<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('api_keys', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('service'); // 'serpapi', 'aviationstack', etc.
            $table->boolean('is_valid')->default(true);
            $table->integer('remaining_requests')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('invalid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('api_keys');
    }
};