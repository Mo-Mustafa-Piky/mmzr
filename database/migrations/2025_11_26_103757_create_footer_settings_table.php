<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('footer_settings', function (Blueprint $table) {
            $table->id();
            $table->string('column_1_title')->default('Explore');
            $table->json('column_1_items')->nullable();
            $table->boolean('column_1_is_active')->default(true);
            $table->string('column_2_title')->default('Services');
            $table->json('column_2_items')->nullable();
            $table->boolean('column_2_is_active')->default(true);
            $table->string('column_3_title')->default('Insights');
            $table->json('column_3_items')->nullable();
            $table->boolean('column_3_is_active')->default(true);
            $table->string('column_4_title')->default('About');
            $table->json('column_4_items')->nullable();
            $table->boolean('column_4_is_active')->default(true);
            $table->string('column_5_title')->nullable();
            $table->json('column_5_items')->nullable();
            $table->boolean('column_5_is_active')->default(false);
            $table->string('copyright_text')->default('Copyright © 2025 Mamzr. All Rights Reserved.');
            $table->string('powered_by_text')->default('Powered by PikyHost');
            $table->string('powered_by_link')->nullable();
            $table->string('privacy_link')->nullable();
            $table->string('terms_link')->nullable();
            $table->string('sitemap_link')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('footer_settings');
    }
};
