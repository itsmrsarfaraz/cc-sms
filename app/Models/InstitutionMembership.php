<?php

namespace App\Models;

use App\Enums\MembershipStatus;
use Database\Factories\InstitutionMembershipFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// user_id and institution_id are deliberately NOT mass-assignable. Memberships
// are created by a dedicated action (added later) that sets them explicitly,
// never from request arrays.
#[Fillable([
    'status',
    'joined_at',
    'ended_at',
])]
class InstitutionMembership extends Model
{
    /** @use HasFactory<InstitutionMembershipFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => MembershipStatus::class,
            'joined_at' => 'datetime',
            'ended_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }
}