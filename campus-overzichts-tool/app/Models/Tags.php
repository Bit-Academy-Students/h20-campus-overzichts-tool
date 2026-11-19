<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tags extends Model
{
    use HasFactory;

    protected $table = 'tags';

    public $timestamps = false;

    protected $fillable = ['name'];

    public function assignments(): BelongsToMany
    {
        return $this->belongsToMany(Assignments::class, 'assignment_tags', 'tag_id', 'assignment_id');
    }

    public function opdrachtnemers(): BelongsToMany
    {
        return $this->belongsToMany(Opdrachtnemer::class, 'opdrachtnemer_tags', 'tag_id', 'opdrachtnemer_id');
    }
}
