<?php

namespace Shep\Coach\Traits\Models;


use Shep\Coach\Models\Position;
use Shep\Coach\Models\Result;

trait User
{
    public function results()
    {
        return $this->hasOne(Result::class);
    }

    public function positions()
    {
        return $this->belongsToMany(Position::class, 't_position_users');
    }

    public function mentorsPositions()
    {
        return $this->belongsToMany(Position::class, 't_position_mentors');
    }

    public function scopeCoach($query)
    {
        return $query->has('positions');
    }

    public function scopeNoCoach($query)
    {
        return $query->doesntHave('positions');
    }

    public function getContactAttribute()
    {
        return $this->name. ' '. $this->last_name;
    }

    public function getRating($user = 0)
    {
        if(!$user)
            $user = $this;//auth()->user();

        $rating = round($user->positions->sum('rating')/$user->positions->count());

        return $rating;
    }

    public function getAvatarAttribute($value)
    {
        return null;
    }
}
