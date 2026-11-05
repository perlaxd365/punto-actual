<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->boolean('in_cover')->default(false);
            $table->timestamp('cover_at')->nullable();
        });

        Schema::dropIfExists('covers');
    }

    public function down(): void
    {
        Schema::create('covers', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('subtitle', 500)->nullable();
            $table->string('image', 2048);
            $table->string('link', 2048)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::table('news', function (Blueprint $table) {
            $table->dropColumn(['in_cover', 'cover_at']);
        });
    }
};
