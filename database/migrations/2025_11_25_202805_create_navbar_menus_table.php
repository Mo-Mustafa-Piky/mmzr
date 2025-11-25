<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('navbar_menus', function (Blueprint $table) {
            $table->id();
            $table->json('menu_items')->nullable();
            $table->string('cta_label')->nullable();
            $table->string('cta_url')->nullable();
            $table->timestamps();
        });

        DB::table('navbar_menus')->insert([
            'menu_items' => json_encode([
                ['label' => 'Home', 'url' => '/', 'is_active' => true, 'open_in_new_tab' => false],
                ['label' => 'Buy', 'url' => '/buy', 'is_active' => true, 'open_in_new_tab' => false],
                ['label' => 'Rent', 'url' => '/rent', 'is_active' => true, 'open_in_new_tab' => false],
                ['label' => 'Off Plan', 'url' => '/off-plan', 'is_active' => true, 'open_in_new_tab' => false],
                ['label' => 'Commercial', 'url' => '/commercial', 'is_active' => true, 'open_in_new_tab' => false],
                ['label' => 'International', 'url' => '/international', 'is_active' => true, 'open_in_new_tab' => false],
                ['label' => 'Featured Projects', 'url' => '/featured-projects', 'is_active' => true, 'open_in_new_tab' => false],
                ['label' => 'Luxury', 'url' => '/luxury', 'is_active' => true, 'open_in_new_tab' => false],
                ['label' => 'Services', 'url' => '/services', 'is_active' => true, 'open_in_new_tab' => false],
                ['label' => 'Contact us', 'url' => '/contact-us', 'is_active' => true, 'open_in_new_tab' => false],
            ]),
            'cta_label' => 'List Your Property',
            'cta_url' => '/list-property',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('navbar_menus');
    }
};
