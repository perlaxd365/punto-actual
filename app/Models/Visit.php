<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'visited_on' => 'date',
            'visited_at' => 'datetime',
        ];
    }
}
