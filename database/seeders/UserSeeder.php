<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole   = Role::where('name', 'admin')->first();
        $staffRole   = Role::where('name', 'staff')->first();
        $managerRole = Role::where('name', 'manager')->first();

        $users = [
            [
                'role_id'  => $adminRole->id,
                'name'     => 'Administrator Inlife',
                'email'    => 'admin@inlife.co.id',
                'password' => Hash::make('password'),
                'phone'    => '081234567890',
                'is_active' => true,
            ],
            [
                'role_id'  => $staffRole->id,
                'name'     => 'Budi Santoso',
                'email'    => 'staff@inlife.co.id',
                'password' => Hash::make('password'),
                'phone'    => '081298765432',
                'is_active' => true,
            ],
            [
                'role_id'  => $staffRole->id,
                'name'     => 'Sari Dewi',
                'email'    => 'staff2@inlife.co.id',
                'password' => Hash::make('password'),
                'phone'    => '082112345678',
                'is_active' => true,
            ],
            [
                'role_id'  => $managerRole->id,
                'name'     => 'Andi Wijaya',
                'email'    => 'manager@inlife.co.id',
                'password' => Hash::make('password'),
                'phone'    => '081356789012',
                'is_active' => true,
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(['email' => $user['email']], $user);
        }

        $this->command->info('✅ Users seeded successfully.');
        $this->command->table(
            ['Role', 'Email', 'Password'],
            [
                ['Admin',   'admin@inlife.co.id',   'password'],
                ['Staff',   'staff@inlife.co.id',   'password'],
                ['Staff 2', 'staff2@inlife.co.id',  'password'],
                ['Manager', 'manager@inlife.co.id', 'password'],
            ]
        );
    }
}
