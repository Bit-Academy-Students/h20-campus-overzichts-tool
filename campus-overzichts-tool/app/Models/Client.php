<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use HasFactory;

    protected $table = 'clients';

    public const UPDATED_AT = null;

    protected $fillable = ['name', 'description', 'website', 'email', 'phone', 'address', 'city'];

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'client_tags', 'client_id', 'tag_id');
    }

    public function contactPersons(): HasMany
    {
        return $this->hasMany(ContactPerson::class, 'client_id');
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class, 'client_id');
    }
}
