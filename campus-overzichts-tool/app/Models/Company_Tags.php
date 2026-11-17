<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Company_Tags extends Pivot
{
    protected $table = 'company_tags';

    public $timestamps = false;

    public $incrementing = false;

    protected $fillable = ['company_id', 'tag_id'];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Companies::class, 'company_id');
    }

    public function tag(): BelongsTo
    {
        return $this->belongsTo(Tags::class, 'tag_id');
    }
}
