<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🚀 Seeding Inlife Inventory Database...');
        $this->command->newLine();

        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            CategorySeeder::class,
            ItemSeeder::class,
            BorrowingSeeder::class,
            SettingSeeder::class,
        ]);

        $this->command->newLine();
        $this->command->info('✅ Database seeded successfully!');
        $this->command->newLine();
        $this->command->info('🔑 Test Accounts:');
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
