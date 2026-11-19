<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class OpdrachtnemerTag extends Pivot
{
    protected $table = 'opdrachtnemer_tags';

    public $timestamps = false;

    public $incrementing = false;

    protected $fillable = ['opdrachtnemer_id', 'tag_id'];

    public function opdrachtnemer(): BelongsTo
    {
        return $this->belongsTo(Opdrachtnemer::class, 'opdrachtnemer_id');
    }

    public function tag(): BelongsTo
    {
        return $this->belongsTo(Tags::class, 'tag_id');
    }
}
