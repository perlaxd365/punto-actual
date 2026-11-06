<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entity_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title', 220);
            $table->string('slug')->unique();
            $table->string('area', 120)->index();
            $table->string('modality', 60)->nullable();
            $table->string('level', 60)->nullable();
            $table->string('duration', 120)->nullable();
            $table->string('excerpt', 500)->nullable();
            $table->longText('description')->nullable();
            $table->string('image')->nullable();
            $table->string('official_url', 2048);
            $table->string('status')->default('draft');
            $table->boolean('is_featured')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->string('seo_title')->nullable();
            $table->string('seo_description', 320)->nullable();
            $table->timestamps();
            $table->index(['status', 'published_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
