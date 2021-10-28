<?php

namespace Shep\Coach\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;


class Position extends Model
{
    use \Shep\Coach\Traits\Models\Funcs;
    protected $table = 't_positions';
    protected $fillable = ['name','active', 'avatar', 'map'];
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

    public function data($ids = null)
    {
        if($ids == null)
            return $this->tasks()->withPivot(['parent_id', 'data']);
        if(!is_array($ids))
            $ids = [$ids];
        return $this->tasks()->whereIn('task_id', $ids)->withPivot(['parent_id', 'data']);
    }

    public function howManyTasks($status = 0)
    {
        if(!$status){
            $count = $this->tasks()->count();
        }
        else{
            $count = $this->tasks()->whereHas('results', function ($q) use($status){
                $q->where('status', $status);
            })->count();
        }

        return $count;
    }

    public function getRatingAttribute()
    {
        $count_task = $this->howManyTasks();
        $ret = 0;
        foreach ($this->tasks as $task)
        {
            $result = $task->results()->where('user_id', $this->pivot->user_id)->where('status', Result::STATUS_FINISHED_OK)->orderByDesc('id')->first();
            if($result)
                $ret+=$result->result;
        }
        return round($ret / $count_task);
    }
}
