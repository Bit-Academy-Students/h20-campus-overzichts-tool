<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tags extends Model
{
    protected $table = 'tags';

    public $timestamps = false;

    protected $fillable = ['name'];

    public function assignments(): BelongsToMany
    {
        return $this->belongsToMany(Assignments::class, 'assignment_tags', 'tag_id', 'assignment_id');
    }

    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Companies::class, 'company_tags', 'tag_id', 'company_id');
    }
}
