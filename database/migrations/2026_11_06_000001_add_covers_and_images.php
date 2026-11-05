<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vacancies', function (Blueprint $table) {
            $table->string('image', 2048)->nullable();
        });

        Schema::table('entities', function (Blueprint $table) {
            $table->string('cover_image', 2048)->nullable();
        });

        Schema::create('covers', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('subtitle', 500)->nullable();
            $table->string('image', 2048);
            $table->string('link', 2048)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index(['is_active', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('covers');

        Schema::table('entities', function (Blueprint $table) {
            $table->dropColumn('cover_image');
        });

        Schema::table('vacancies', function (Blueprint $table) {
            $table->dropColumn('image');
        });
    }
};
