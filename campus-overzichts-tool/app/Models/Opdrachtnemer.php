<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Opdrachtnemer extends Model
{
    use HasFactory;

    protected $table = 'opdrachtnemers';

    public const UPDATED_AT = null;

    protected $fillable = ['name', 'description', 'website', 'email', 'phone', 'address', 'city'];

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tags::class, 'opdrachtnemer_tags', 'opdrachtnemer_id', 'tag_id');
    }

    public function claims(): HasMany
    {
        return $this->hasMany(AssignmentClaim::class, 'opdrachtnemer_id');
    }
}
