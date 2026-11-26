<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class AssignmentTag extends Pivot
{
    protected $table = 'assignment_tags';

    public $timestamps = false;

    public $incrementing = false;

    protected $fillable = ['assignment_id', 'tag_id'];

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(Assignment::class, 'assignment_id');
    }

    public function tag(): BelongsTo
    {
        return $this->belongsTo(Tag::class, 'tag_id');
    }
}
