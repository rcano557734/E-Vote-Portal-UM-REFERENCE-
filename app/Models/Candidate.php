<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    protected $fillable = ['position_id', 'candidate_name', 'platform_description'];

        public function position() { 
            return $this->belongsTo(Position::class); 
        }

        public function votes() {
        return $this->hasMany(Vote::class);
    }
}
