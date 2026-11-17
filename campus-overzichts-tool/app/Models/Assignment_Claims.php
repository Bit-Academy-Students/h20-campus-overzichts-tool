<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Assignment_Claims extends Model
{
    protected $table = 'assignment_claims';

    public $timestamps = false;

    protected $fillable = ['assignment_id', 'user_id', 'company_id', 'status', 'claimed_at', 'assigned_at'];

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

    public function company(): BelongsTo
    {
        return $this->belongsTo(Companies::class, 'company_id');
    }
}
