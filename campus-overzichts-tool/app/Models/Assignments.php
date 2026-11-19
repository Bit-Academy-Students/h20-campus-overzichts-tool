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

    protected $table = 'assignments';

    public $timestamps = false;

    protected $fillable = ['opdrachtgever_id', 'title', 'description', 'source_url', 'posted_at', 'scraped_at'];

    protected function casts(): array
    {
        return [
            'posted_at' => 'date',
            'scraped_at' => 'datetime',
        ];
    }

    public function opdrachtgever(): BelongsTo
    {
        return $this->belongsTo(Opdrachtgever::class, 'opdrachtgever_id');
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
