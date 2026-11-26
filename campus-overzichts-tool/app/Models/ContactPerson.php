<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use InvalidArgumentException;

class ContactPerson extends Model
{
    use HasFactory;

    protected $table = 'contact_persons';

    public const UPDATED_AT = null;

    protected $fillable = ['client_id', 'contractor_id', 'first_name', 'last_name', 'job_title', 'email', 'phone'];

    protected static function booted(): void
    {
        // Een contactpersoon hoort bij precies één client OF één contractor.
        static::saving(function (ContactPerson $contactPerson) {
            if (($contactPerson->client_id === null) === ($contactPerson->contractor_id === null)) {
                throw new InvalidArgumentException('A contact person must belong to exactly one client or one contractor.');
            }
        });
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function contractor(): BelongsTo
    {
        return $this->belongsTo(Contractor::class, 'contractor_id');
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class, 'contact_person_id');
    }
}
