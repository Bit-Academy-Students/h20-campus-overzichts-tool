<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Assignments extends Model
{
    use HasFactory;

    public const STATUSES = ['new', 'in_review', 'accepted', 'rejected', 'assigned'];

    public const PRIORITIES = ['high', 'medium', 'low'];

    protected $table = 'assignments';

    public $timestamps = false;

    protected $fillable = ['client_id', 'contact_person_id', 'title', 'description', 'source_url', 'status', 'priority', 'posted_at', 'scraped_at'];

    protected function casts(): array
    {
        return [
            'posted_at' => 'date',
            'scraped_at' => 'datetime',
        ];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function contactPerson(): BelongsTo
    {
        return $this->belongsTo(ContactPerson::class, 'contact_person_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tags::class, 'assignment_tags', 'assignment_id', 'tag_id');
    }

    public function claim(): HasOne
    {
        return $this->hasOne(AssignmentClaim::class, 'assignment_id');
    }

    public function favoritedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'assignment_favorites', 'assignment_id', 'user_id');
    }
}
