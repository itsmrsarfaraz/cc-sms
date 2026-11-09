<?php

namespace App\Models;

use App\Enums\InstitutionStatus;
use App\Enums\InstitutionType;
use Database\Factories\InstitutionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'name',
    'slug',
    'type',
    'status',
    'registration_number',
    'email',
    'phone',
    'address',
    'city',
    'country_code',
    'timezone',
    'currency',
    'logo_path',
])]
class Institution extends Model
{
    /** @use HasFactory<InstitutionFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => InstitutionType::class,
            'status' => InstitutionStatus::class,
        ];
    }

    public function campuses(): HasMany
    {
        return $this->hasMany(Campus::class);
    }
}