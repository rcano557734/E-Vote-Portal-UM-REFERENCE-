<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
        protected $fillable = [
        'user_id',
        'candidate_id',
        'election_id',
        'position_id'
    ];

    // Tell Laravel that every vote belongs to a specific candidate
    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

    // Tell Laravel that every vote belongs to a specific user/student
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function election() {
        return $this->belongsTo(Election::class);
    }

    public function position() {
        return $this->belongsTo(Position::class);
    }
}
