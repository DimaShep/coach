<?php

namespace Shep\Coach\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;


class Position extends Model
{
    use \Shep\Coach\Traits\Models\Funcs;
    protected $table = 't_positions';
    protected $fillable = ['name','active', 'avatar', 'map', 'auto_reset'];
    protected $casts = ['active'=>'boolean', 'map'=>'array'];



    public function mentors()
    {
        return $this->belongsToMany(User::class,'t_position_mentors');
    }

    public function users()
    {
        return $this->belongsToMany(User::class,'t_position_users');
    }

    public function tasks()
    {
        return $this->belongsToMany(Task::class,'t_position_tasks')->using(PositionTaskPivot::class);
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function data($ids = null)
    {
        if($ids == null)
            return $this->tasks()->withPivot(['parent_id', 'data']);
        if(!is_array($ids))
            $ids = [$ids];
        return $this->tasks()->whereIn('task_id', $ids)->withPivot(['parent_id', 'data']);
    }

    public function howManyTasks($status = 0, $user_id = 0)
    {
        if(!$status){
            $count = $this->tasks()->count();
        }
        else{
            $count = $this->tasks()->whereHas('allResults', function ($q) use($status, $user_id){
                $q->where('status', $status);
                if($user_id)
                    $q->where('user_id', $user_id);
            });
            $count = $count->count();
        }

        return $count;
    }

    public function getCountTasksAttribute()
    {
        return $this->howManyTasks();
    }

    public function getCountFinishedTasksAttribute()
    {
        return $this->howManyTasks(Result::STATUS_FINISHED_OK, $this->pivot->user_id);
    }

    public function getRatingAttribute()
    {
        $count_task = $this->howManyTasks();
        if(!$count_task)
            return 0;
        $ret = 0;
        $penalty = 0;
        foreach ($this->tasks as $task)
        {
            $result = $task->results($this->pivot->user_id)->where('user_id', $this->pivot->user_id)->where('status', Result::STATUS_FINISHED_OK)->orderByDesc('id')->first();
            if($result)
                $ret+=$result->result;
            $penalty += $task->results($this->pivot->user_id)->where('user_id', $this->pivot->user_id)->where('status', Result::STATUS_FINISHED_FILED)->sum('penalty');
        }
        return round($ret / $count_task)-$penalty;
    }

    function getDayToAutoreset()
    {
        if($this->auto_reset == 0)
            return '-';

        $results = collect();
        foreach ($this->tasks as $task){
            $result = $task->allResults()->orderByDesc('created_at')->first();
            if($result)
                $results->push($result);
        }
        $result = $results->min('created_at');
        if(!$result)
            return now()->addMonth($this->auto_reset)->diffInDays(now());
        return \Carbon\Carbon::parse($result)->addMonth($this->auto_reset)->diffInDays(now());
    }

    public function deletePosition()
    {
        foreach ($this->tasks as $task){
            if($task->positions()->count == 1){
                $task->deleteTask();
            }
        }
    }
}
