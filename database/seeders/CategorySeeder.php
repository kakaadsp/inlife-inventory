<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['code' => 'ELK', 'name' => 'Elektronik',         'description' => 'Perangkat elektronik seperti laptop, monitor, printer, dll.'],
            ['code' => 'PKN', 'name' => 'Peralatan Kantor',   'description' => 'Peralatan dan perlengkapan kebutuhan kantor sehari-hari.'],
            ['code' => 'FRN', 'name' => 'Furnitur',           'description' => 'Meja, kursi, lemari, dan perabot kantor lainnya.'],
            ['code' => 'KMN', 'name' => 'Komunikasi',         'description' => 'Perangkat komunikasi seperti telepon, headset, radio, dll.'],
            ['code' => 'LIN', 'name' => 'Lainnya',            'description' => 'Barang inventaris yang tidak termasuk kategori di atas.'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(['code' => $category['code']], $category);
        }

        $this->command->info('✅ Categories seeded successfully.');
    }
}
