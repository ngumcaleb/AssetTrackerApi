<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CheckOut extends Model
{
    use HasFactory;

    protected $table = 'checkouts';

    protected $fillable = [
        'asset_id',
        'user_id',
        'assignee_name',
        'department',
        'purpose',
        'destination',
        'expected_return',
        'notes',
        'checked_out_at',
        'returned_at',
        'return_notes',
    ];

    protected function casts(): array
    {
        return [
            'expected_return' => 'date',
            'checked_out_at' => 'datetime',
            'returned_at' => 'datetime',
        ];
    }

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
