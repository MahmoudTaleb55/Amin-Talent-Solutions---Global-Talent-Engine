<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Extend the users.role enum to include 'ceo' (MySQL only)
        try {
            $driver = DB::getDriverName();
            if ($driver === 'mysql') {
                DB::statement("ALTER TABLE `users` MODIFY `role` ENUM('admin','company','freelancer','ceo') NOT NULL");
            }
        } catch (\Throwable $e) {
            // Leave as no-op for other drivers (e.g., sqlite) or if already altered
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original enum without 'ceo'
        try {
            $driver = DB::getDriverName();
            if ($driver === 'mysql') {
                DB::statement("ALTER TABLE `users` MODIFY `role` ENUM('admin','company','freelancer') NOT NULL");
            }
        } catch (\Throwable $e) {
            // no-op
        }
    }
};
