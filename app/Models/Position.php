<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    // ---> THIS IS THE MISSING PIECE <---
    protected $fillable = [
        'position_name',
        'election_id'
    ];

    public function election() {
        return $this->belongsTo(Election::class);
    }

    public function candidates() {
        return $this->hasMany(Candidate::class);
    }
}