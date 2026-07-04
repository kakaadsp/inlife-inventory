<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@telkomsel.com')->first();
        $staff = User::where('email', 'staff@telkomsel.com')->first();

        $elk = Category::where('code', 'ELK')->first();
        $pkn = Category::where('code', 'PKN')->first();
        $frn = Category::where('code', 'FRN')->first();
        $kmn = Category::where('code', 'KMN')->first();
        $lin = Category::where('code', 'LIN')->first();

        $items = [
            // Elektronik
            ['category_id' => $elk->id, 'code' => 'TSEL-ELK-0001', 'name' => 'Laptop Lenovo ThinkPad X1 Carbon', 'stock' => 8,  'min_stock' => 3, 'location' => 'Gudang A - Rak 1', 'condition' => 'good',    'created_by' => $admin->id],
            ['category_id' => $elk->id, 'code' => 'TSEL-ELK-0002', 'name' => 'Monitor LG UltraWide 34 inch',      'stock' => 5,  'min_stock' => 2, 'location' => 'Gudang A - Rak 1', 'condition' => 'good',    'created_by' => $admin->id],
            ['category_id' => $elk->id, 'code' => 'TSEL-ELK-0003', 'name' => 'Printer Canon PIXMA G2020',         'stock' => 3,  'min_stock' => 2, 'location' => 'Gudang A - Rak 2', 'condition' => 'good',    'created_by' => $admin->id],
            ['category_id' => $elk->id, 'code' => 'TSEL-ELK-0004', 'name' => 'Proyektor Epson EB-X51',            'stock' => 2,  'min_stock' => 1, 'location' => 'Gudang A - Rak 2', 'condition' => 'fair',    'created_by' => $admin->id],
            ['category_id' => $elk->id, 'code' => 'TSEL-ELK-0005', 'name' => 'UPS APC Smart 1500VA',             'stock' => 4,  'min_stock' => 2, 'location' => 'Gudang B - Rak 1', 'condition' => 'good',    'created_by' => $staff->id],
            ['category_id' => $elk->id, 'code' => 'TSEL-ELK-0006', 'name' => 'Mouse Wireless Logitech MX Master', 'stock' => 2,  'min_stock' => 5, 'location' => 'Gudang B - Rak 2', 'condition' => 'good',    'created_by' => $staff->id], // LOW STOCK
            ['category_id' => $elk->id, 'code' => 'TSEL-ELK-0007', 'name' => 'Keyboard Mechanical Keychron K2',  'stock' => 1,  'min_stock' => 3, 'location' => 'Gudang B - Rak 2', 'condition' => 'good',    'created_by' => $staff->id], // LOW STOCK
            ['category_id' => $elk->id, 'code' => 'TSEL-ELK-0008', 'name' => 'Webcam Logitech C920 HD Pro',      'stock' => 6,  'min_stock' => 2, 'location' => 'Gudang B - Rak 3', 'condition' => 'good',    'created_by' => $staff->id],

            // Peralatan Kantor
            ['category_id' => $pkn->id, 'code' => 'TSEL-PKN-0001', 'name' => 'Stapler Besar Kangaro HD-23',      'stock' => 10, 'min_stock' => 3, 'location' => 'Gudang C - Rak 1', 'condition' => 'good',    'created_by' => $admin->id],
            ['category_id' => $pkn->id, 'code' => 'TSEL-PKN-0002', 'name' => 'Penghancur Kertas Fujika FJ-888',  'stock' => 3,  'min_stock' => 2, 'location' => 'Gudang C - Rak 1', 'condition' => 'fair',    'created_by' => $admin->id],
            ['category_id' => $pkn->id, 'code' => 'TSEL-PKN-0003', 'name' => 'Whiteboard 120x180 cm',            'stock' => 4,  'min_stock' => 2, 'location' => 'Gudang C - Rak 2', 'condition' => 'good',    'created_by' => $staff->id],
            ['category_id' => $pkn->id, 'code' => 'TSEL-PKN-0004', 'name' => 'Extension Kabel 5 Meter 6 Lubang', 'stock' => 1,  'min_stock' => 4, 'location' => 'Gudang C - Rak 2', 'condition' => 'damaged', 'created_by' => $staff->id], // LOW STOCK + DAMAGED

            // Furnitur
            ['category_id' => $frn->id, 'code' => 'TSEL-FRN-0001', 'name' => 'Kursi Ergonomis Herman Miller',    'stock' => 5,  'min_stock' => 2, 'location' => 'Gudang D - Bagian 1', 'condition' => 'good', 'created_by' => $admin->id],
            ['category_id' => $frn->id, 'code' => 'TSEL-FRN-0002', 'name' => 'Meja Rapat Oval 8 Orang',         'stock' => 2,  'min_stock' => 1, 'location' => 'Gudang D - Bagian 1', 'condition' => 'good', 'created_by' => $admin->id],
            ['category_id' => $frn->id, 'code' => 'TSEL-FRN-0003', 'name' => 'Lemari Arsip 4 Laci',             'stock' => 3,  'min_stock' => 1, 'location' => 'Gudang D - Bagian 2', 'condition' => 'fair', 'created_by' => $staff->id],

            // Komunikasi
            ['category_id' => $kmn->id, 'code' => 'TSEL-KMN-0001', 'name' => 'Telephone IP Cisco 7945G',        'stock' => 7,  'min_stock' => 3, 'location' => 'Gudang E - Rak 1', 'condition' => 'good',    'created_by' => $admin->id],
            ['category_id' => $kmn->id, 'code' => 'TSEL-KMN-0002', 'name' => 'Headset Plantronics CS540',       'stock' => 4,  'min_stock' => 3, 'location' => 'Gudang E - Rak 1', 'condition' => 'good',    'created_by' => $admin->id],
            ['category_id' => $kmn->id, 'code' => 'TSEL-KMN-0003', 'name' => 'Router WiFi TP-Link Archer AX50', 'stock' => 3,  'min_stock' => 1, 'location' => 'Gudang E - Rak 2', 'condition' => 'good',    'created_by' => $staff->id],

            // Lainnya
            ['category_id' => $lin->id, 'code' => 'TSEL-LIN-0001', 'name' => 'Kamera Mirrorless Sony A6400',    'stock' => 2,  'min_stock' => 1, 'location' => 'Brankas Utama',     'condition' => 'good',    'created_by' => $admin->id],
            ['category_id' => $lin->id, 'code' => 'TSEL-LIN-0002', 'name' => 'Tripod Manfrotto MT190',          'stock' => 1,  'min_stock' => 1, 'location' => 'Brankas Utama',     'condition' => 'fair',    'created_by' => $admin->id],
        ];

        foreach ($items as $item) {
            Item::updateOrCreate(['code' => $item['code']], $item);
        }

        $this->command->info('✅ Items seeded successfully. (20 items, 4 low stock items included)');
    }
}
