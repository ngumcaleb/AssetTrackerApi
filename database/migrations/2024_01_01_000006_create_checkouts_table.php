<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('checkouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('assignee_name');
            $table->string('department')->nullable();
            $table->string('purpose')->nullable();
            $table->string('destination')->nullable();
            $table->date('expected_return')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('checked_out_at')->useCurrent();
            $table->timestamp('returned_at')->nullable();
            $table->text('return_notes')->nullable();
            $table->timestamps();

            $table->index('asset_id');
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('checkouts');
    }
};
