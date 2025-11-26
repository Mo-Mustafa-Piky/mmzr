<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('awards_banners', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('button_one_text')->default('Work With Us');
            $table->string('button_one_link')->nullable();
            $table->string('button_two_text')->default('Our Story');
            $table->string('button_two_link')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('awards_banners');
    }
};
