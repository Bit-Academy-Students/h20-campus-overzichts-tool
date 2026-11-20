<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contractor extends Model
{
    use HasFactory;

    protected $table = 'contractors';

    public const UPDATED_AT = null;

    protected $fillable = ['name', 'description', 'website', 'email', 'phone', 'address', 'city'];

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tags::class, 'contractor_tags', 'contractor_id', 'tag_id');
    }

    public function contactPersons(): HasMany
    {
        return $this->hasMany(ContactPerson::class, 'contractor_id');
    }

    public function claims(): HasMany
    {
        return $this->hasMany(AssignmentClaim::class, 'contractor_id');
    }
}
