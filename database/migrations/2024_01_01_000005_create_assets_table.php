<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('asset_tag')->unique();
            $table->string('serial')->unique();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->enum('status', ['active', 'archived', 'checked_out'])->default('active');
            $table->string('photo_url')->nullable();
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->date('purchase_date')->nullable();
            $table->decimal('purchase_price', 12, 2)->nullable();
            $table->string('supplier')->nullable();
            $table->string('location')->nullable();
            $table->text('description')->nullable();
            $table->timestamp('archived_at')->nullable();
            $table->string('archived_reason')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('status');
            $table->index('category_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
