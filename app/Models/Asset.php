<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'asset_tag',
        'serial',
        'category_id',
        'status',
        'photo_url',
        'brand',
        'model',
        'purchase_date',
        'purchase_price',
        'supplier',
        'location',
        'description',
        'archived_at',
        'archived_reason',
        'condition',
        'discarded_at',
        'discarded_reason',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'purchase_date' => 'date',
            'purchase_price' => 'decimal:2',
            'archived_at' => 'datetime',
            'discarded_at' => 'datetime',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function checkouts(): HasMany
    {
        return $this->hasMany(CheckOut::class);
    }

    public function currentCheckout(): HasOne
    {
        return $this->hasOne(CheckOut::class)->whereNull('returned_at');
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function scopeArchived($query)
    {
        return $query->where('status', 'archived');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeCheckedOut($query)
    {
        return $query->where('status', 'checked_out');
    }

    public function scopeDiscarded($query)
    {
        return $query->where('status', 'discarded');
    }
}
