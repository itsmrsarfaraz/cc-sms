<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// Nothing is mass-assignable: assignments are created only by a dedicated
// action (added later) that sets every column explicitly after authorization.
class RoleAssignment extends Model
{
    protected $guarded = ['*'];

    public function membership(): BelongsTo
    {
        return $this->belongsTo(InstitutionMembership::class, 'membership_id');
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function campus(): BelongsTo
    {
        return $this->belongsTo(Campus::class);
    }
}