<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contactpersoon extends Model
{
    use HasFactory;

    protected $table = 'contactpersonen';

    public const UPDATED_AT = null;

    protected $fillable = ['opdrachtgever_id', 'first_name', 'last_name', 'function', 'email', 'phone'];

    public function opdrachtgever(): BelongsTo
    {
        return $this->belongsTo(Opdrachtgever::class, 'opdrachtgever_id');
    }
}
