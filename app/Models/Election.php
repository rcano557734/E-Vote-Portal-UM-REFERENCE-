<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Election extends Model
{
    protected $fillable = ['title', 'start_date', 'end_date', 'status'];

    public function positions() {
        return $this->hasMany(Position::class);
    }

    public function votes() {
        return $this->hasMany(Vote::class);
    }
}