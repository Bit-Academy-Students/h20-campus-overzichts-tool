<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssignmentClaim extends Model
{
    use HasFactory;

    protected $table = 'assignment_claims';

    public $timestamps = false;

    protected $fillable = ['assignment_id', 'user_id', 'contractor_id', 'status', 'claimed_at', 'assigned_at'];

    protected function casts(): array
    {
        return [
            'claimed_at' => 'datetime',
            'assigned_at' => 'datetime',
        ];
    }

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(Assignments::class, 'assignment_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function contractor(): BelongsTo
    {
        return $this->belongsTo(Contractor::class, 'contractor_id');
    }
}
