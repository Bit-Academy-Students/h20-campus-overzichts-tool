<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ContractorTag extends Pivot
{
    protected $table = 'contractor_tags';

    public $timestamps = false;

    public $incrementing = false;

    protected $fillable = ['contractor_id', 'tag_id'];

    public function contractor(): BelongsTo
    {
        return $this->belongsTo(Contractor::class, 'contractor_id');
    }

    public function tag(): BelongsTo
    {
        return $this->belongsTo(Tags::class, 'tag_id');
    }
}
