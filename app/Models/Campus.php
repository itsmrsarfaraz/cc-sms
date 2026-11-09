<?php

namespace App\Models;

use App\Enums\CampusStatus;
use Database\Factories\CampusFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// institution_id is deliberately NOT mass-assignable. Campuses are created
// through $institution->campuses()->create([...]) so the owner is never
// taken from request data.
#[Fillable([
    'name',
    'code',
    'status',
    'email',
    'phone',
    'address',
    'city',
])]
class Campus extends Model
{
    /** @use HasFactory<CampusFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => CampusStatus::class,
        ];
    }

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }
}