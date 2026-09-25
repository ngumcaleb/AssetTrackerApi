<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            // Manually entered asset code (visible identifier, e.g. company-specific codes)
            $table->string('asset_code')->nullable()->unique()->after('asset_tag');
            // Person responsible for the asset
            $table->string('assigned_custodian')->nullable()->after('location');
        });
    }

    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn(['asset_code', 'assigned_custodian']);
        });
    }
};
