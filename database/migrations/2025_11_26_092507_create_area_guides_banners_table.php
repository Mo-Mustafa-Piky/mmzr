<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('area_guides_banners', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('download_button_text')->default('Download');
            $table->string('download_link')->nullable();
            $table->string('video_button_text')->default('Watch Video');
            $table->string('video_link')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('area_guides_banners');
    }
};
