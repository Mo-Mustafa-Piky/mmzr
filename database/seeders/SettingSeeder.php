<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::firstOrCreate([], [
            'site_name' => 'MMZR Platform',
            'site_description' => 'A comprehensive platform for managing your business operations efficiently and effectively.',
            'site_email' => 'info@mmzr.com',
            'site_phone' => '+1 (555) 123-4567',
            'site_address' => '123 Business Street, Suite 100, City, State 12345',
            'footer_text' => '© 2024 MMZR Platform. All rights reserved. Empowering businesses worldwide.',
            'facebook' => 'https://facebook.com/mmzr',
            'twitter' => 'https://twitter.com/mmzr',
            'instagram' => 'https://instagram.com/mmzr',
            'linkedin' => 'https://linkedin.com/company/mmzr',
            'youtube' => 'https://youtube.com/@mmzr',
            'whatsapp' => '+15551234567',
            'telegram' => 'https://t.me/mmzr',
            'meta_keywords' => 'business, platform, management, operations, efficiency, productivity',
            'meta_description' => 'MMZR Platform - Your complete solution for business management and operations.',
            'maintenance_mode' => false,
        ]);
    }
}
