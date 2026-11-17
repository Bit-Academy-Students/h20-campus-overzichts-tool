<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sources extends Model
{
    protected $table = 'sources';

    public $timestamps = false;

    protected $fillable = ['name', 'url', 'active'];

    protected function casts(): array
    {
        return ['active' => 'boolean'];
    }
}
