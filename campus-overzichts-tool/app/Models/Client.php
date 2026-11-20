<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Opdrachtgever extends Model
{
    use HasFactory;

    protected $table = 'opdrachtgevers';

    public const UPDATED_AT = null;

    protected $fillable = ['name', 'description', 'website', 'email', 'phone', 'address', 'city'];

    public function contactpersonen(): HasMany
    {
        return $this->hasMany(Contactpersoon::class, 'opdrachtgever_id');
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignments::class, 'opdrachtgever_id');
    }
}
