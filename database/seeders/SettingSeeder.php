<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'app_name',            'value' => 'Inlife Inventory',     'description' => 'Nama aplikasi yang ditampilkan di sistem'],
            ['key' => 'company_name',         'value' => 'Inlife',            'description' => 'Nama perusahaan'],
            ['key' => 'app_version',          'value' => '1.0.0',              'description' => 'Versi aplikasi saat ini'],
            ['key' => 'low_stock_threshold',  'value' => '5',                  'description' => 'Batas minimum stok sebelum alert ditampilkan'],
            ['key' => 'items_per_page',       'value' => '15',                 'description' => 'Jumlah data per halaman pada tabel'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }

        $this->command->info('✅ Settings seeded successfully.');
    }
}
