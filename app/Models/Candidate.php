<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    protected $fillable = [
        'position_id', 
        'candidate_name', 
        'partylist',
        'college', // <--- ADD THIS
        'platform_description', 
        'is_archived'
    ];

    public function position() {
        return $this->belongsTo(Position::class);
    }

    public function votes() {
        return $this->hasMany(Vote::class);
    }
}