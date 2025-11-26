<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('area_guides', function (Blueprint $table) {
            $table->id();
            $table->string('area_name');
            $table->text('description');
            $table->string('badge_text')->default('Updated Read');
            $table->string('button_text')->default('Read Guide');
            $table->string('slug')->unique();
            $table->longText('full_content')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
            
            $table->index('slug');
            $table->index('is_active');
            $table->index('order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('area_guides');
    }
};
