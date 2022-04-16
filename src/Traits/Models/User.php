<?php

namespace Shep\Coach\Traits\Models;


use Carbon\Carbon;
use Shep\Coach\Models\Comment;
use Shep\Coach\Models\Position;
use Shep\Coach\Models\Result;

trait User
{

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function results()
    {
        return $this->hasMany(Result::class);
    }

    public function result()
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

        $tasks = collect();
        foreach ($user->positions as $position)
        {
            //$tasks = $tasks->merge($position->tasks->diff($tasks));
            $tasks = $tasks->merge($position->tasks);
        }
        $count_task = $tasks->count();
        if(!$count_task)
            return 0;

        $ret = 0;
        $penalty = 0;
        foreach ($tasks as $task)
        {
            $result = $task->results($user->id)->where('status', Result::STATUS_FINISHED_OK)->orderByDesc('id')->first();
            if($result)
                $ret+=$result->result;
            $penalty += $task->results($user->id)->where('status', Result::STATUS_FINISHED_FILED)->sum('penalty');
        }
        return round($ret / $count_task)-$penalty;

    }

    public function coutTasks()
    {
        return $this->positions->sum('countTasks');
    }

    public function coutFinishTasks()
    {
        return $this->positions->sum('countFinishedTasks');
    }

    public function coutDaysTested()
    {
        $first = $this->results()->orderBy('id')->first();
        if(!$first)
            return 0;
        $first_data = $first->created_at;
        if($this->coutTasks() < $this->coutFinishTasks())
            $last = now();
        else {
            $last_data = $last->created_at;
        }

        $dif_days = Carbon::parse($first_data)->diffInDays($last_data);

        return $dif_days?$dif_days:1;
    }

    public function startDay()
    {
        $first = $this->results()->orderBy('id')->first();
        if (!$first)
            return 0;
        return Carbon::parse($first->created_at);
    }

    public function lastDay()
    {
        $first = $this->results()->orderByDesc('id')->first();
        if (!$first)
            return 0;
        return Carbon::parse($first->created_at);
    }



    public function getAvatarAttribute($value)
    {
        return null;
    }

    public static function getProcFinish()
    {
        $positions = auth()->user()->positions;
        $all = $finish = 0;
        foreach ($positions as $position) {
            $all += $position->howManyTasks();
            $finish += $position->howManyTasks(Result::STATUS_FINISHED_OK, auth()->user()->id);
        }

        return round($finish * 100 / $all);
    }


    public function getProcPositions()
    {
        $positions = $this->positions;
        $counts = [];
        foreach ($positions as $position) {
            $counts['all'][$position->id] = $position->howManyTasks();
            $counts['finish'][$position->id] = $position->howManyTasks(Result::STATUS_FINISHED_OK, $this->id);
            $counts['checked'][$position->id] = $position->howManyTasks(Result::STATUS_CHECKED, $this->id);
            $counts['proc_finish'][$position->id] = round($counts['finish'][$position->id] * 100 / $counts['all'][$position->id]);
            $counts['proc_checked'][$position->id] = round($counts['checked'][$position->id] * 100 / $counts['all'][$position->id]);

            $counts['all'][0] += $position->howManyTasks();
            $counts['finish'][0] += $position->howManyTasks(Result::STATUS_FINISHED_OK, $this->id);
            $counts['checked'][0] += $position->howManyTasks(Result::STATUS_CHECKED, $this->id);


        }
        $counts['proc_finish'][0] = round($counts['finish'][0] * 100 / $counts['all'][0]);
        $counts['proc_checked'][0] = round($counts['checked'][0] * 100 / $counts['all'][0]);

        return $counts;
    }

    public static function getMentors()
    {
        return User::where('role_id', '!=', User::USER_ROLE_ID)->get();
    }

    public function scopeStudents($query)
    {
        return $query;
    }

    public function scopeNoBlocked($query)
    {
        return $query->where('blocked', 0);
    }

}
