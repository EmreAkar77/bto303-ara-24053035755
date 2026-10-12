<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rental extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'customer_id',
        'pickup_branch_id',
        'return_branch_id',
        'start_date',
        'end_date',
        'total_price',
        'status',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'total_price' => 'decimal:2',
    ];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function pickupBranch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'pickup_branch_id');
    }

    public function returnBranch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'return_branch_id');
    }

    /**
     * Checks if given dates conflict with active/existing rentals for the same vehicle.
     * Delegates directly to Vehicle::isAvailableBetween to ensure single source of truth.
     */
    public static function hasDateConflict(int $vehicleId, string $startDate, string $endDate, ?int $excludeRentalId = null): bool
    {
        $vehicle = Vehicle::find($vehicleId);

        if (! $vehicle) {
            return false;
        }

        return ! $vehicle->isAvailableBetween($startDate, $endDate, $excludeRentalId);
    }
}
