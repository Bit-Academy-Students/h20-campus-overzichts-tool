<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Assignment_Favorites extends Pivot
{
    protected $table = 'assignment_favorites';

    public $timestamps = false;

    public $incrementing = false;

    protected $fillable = ['user_id', 'assignment_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(Assignments::class, 'assignment_id');
    }
}
