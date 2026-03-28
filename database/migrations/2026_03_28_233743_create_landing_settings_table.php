<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('landing_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('label');
            $table->text('value')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('type')->default('toggle'); // toggle, text, number
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('landing_settings');
    }
};
