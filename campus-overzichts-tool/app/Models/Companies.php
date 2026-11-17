<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Companies extends Model
{
    protected $table = 'companies';

    public const UPDATED_AT = null;

    protected $fillable = ['name', 'description', 'website', 'email', 'phone', 'address', 'city'];

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tags::class, 'company_tags', 'company_id', 'tag_id');
    }

    public function claims(): HasMany
    {
        return $this->hasMany(Assignment_Claims::class, 'company_id');
    }
}
