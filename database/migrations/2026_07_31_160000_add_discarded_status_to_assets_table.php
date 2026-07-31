<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE assets MODIFY COLUMN status ENUM('active', 'archived', 'checked_out', 'discarded') NOT NULL DEFAULT 'active'");
        }
    }

    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::table('assets')->where('status', 'discarded')->update(['status' => 'archived']);
            DB::statement("ALTER TABLE assets MODIFY COLUMN status ENUM('active', 'archived', 'checked_out') NOT NULL DEFAULT 'active'");
        }
    }
};
