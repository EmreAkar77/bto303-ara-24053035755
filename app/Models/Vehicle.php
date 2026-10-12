<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'plate_number',
        'brand',
        'model',
        'model_year',
        'daily_rate',
        'status',
    ];

    protected $casts = [
        'daily_rate' => 'decimal:2',
        'model_year' => 'integer',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function rentals(): HasMany
    {
        return $this->hasMany(Rental::class);
    }

    public function getTitleAttribute(): string
    {
        return "{$this->brand} {$this->model} ({$this->plate_number})";
    }

    /**
     * Scope to find vehicles available in a given date range.
     * Vehicle status is checked only for maintenance.
     * Rental overlap excludes cancelled rentals.
     */
    public function scopeAvailableBetween($query, string $startDate, string $endDate, ?int $excludeRentalId = null)
    {
        return $query->where('status', '!=', 'maintenance')
            ->whereDoesntHave('rentals', function ($q) use ($startDate, $endDate, $excludeRentalId) {
                $q->where('status', '!=', 'cancelled')
                    ->when($excludeRentalId, fn ($rq) => $rq->where('id', '!=', $excludeRentalId))
                    ->where('start_date', '<=', $endDate)
                    ->where('end_date', '>=', $startDate);
            });
    }

    /**
     * Check if this vehicle instance is available in a given date range.
     */
    public function isAvailableBetween(string $startDate, string $endDate, ?int $excludeRentalId = null): bool
    {
        if ($this->status === 'maintenance') {
            return false;
        }

        return ! $this->rentals()
            ->where('status', '!=', 'cancelled')
            ->when($excludeRentalId, fn ($rq) => $rq->where('id', '!=', $excludeRentalId))
            ->where('start_date', '<=', $endDate)
            ->where('end_date', '>=', $startDate)
            ->exists();
    }
}
