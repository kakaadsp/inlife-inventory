<?php

// This migration is intentionally empty.
// Roles table is created in 0000_01_01_000000_create_roles_table.php
// which runs before the users table migration.

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void {}
    public function down(): void {}
};
