<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'phone',
    ];

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class);
    }

    public function pickupRentals(): HasMany
    {
        return $this->hasMany(Rental::class, 'pickup_branch_id');
    }

    public function returnRentals(): HasMany
    {
        return $this->hasMany(Rental::class, 'return_branch_id');
    }
}
