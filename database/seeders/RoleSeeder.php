<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name'         => 'admin',
                'display_name' => 'Administrator',
                'description'  => 'Akses penuh ke seluruh sistem',
            ],
            [
                'name'         => 'staff',
                'display_name' => 'Staff Inventaris',
                'description'  => 'Kelola barang dan transaksi peminjaman',
            ],
            [
                'name'         => 'manager',
                'display_name' => 'Manager',
                'description'  => 'Akses laporan dan dashboard (read-only)',
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(['name' => $role['name']], $role);
        }

        $this->command->info('✅ Roles seeded successfully.');
    }
}
