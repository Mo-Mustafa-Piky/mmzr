<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('site_name')->nullable();
            $table->text('site_description')->nullable();
            $table->string('site_email')->nullable();
            $table->string('site_phone')->nullable();
            $table->text('site_address')->nullable();
            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();
            $table->string('footer_logo')->nullable();
            $table->text('footer_text')->nullable();
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('instagram')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('youtube')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('telegram')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('google_analytics')->nullable();
            $table->text('facebook_pixel')->nullable();
            $table->boolean('maintenance_mode')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
