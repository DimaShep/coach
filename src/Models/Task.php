<?php

namespace Shep\Coach\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use Shep\Coach\Mail\UpdateTask;

class Task extends Model
{
    use \Shep\Coach\Traits\Models\Funcs;
    protected $table = 't_tasks';
    protected $fillable = ['name', 'type', 'text', 'questions', 'info', 'time','penalty'];
    protected $casts = [
        'info' => 'array',
        'questions' => 'array'
    ];

    const TYPE_TEST = 'test';
    const TYPE_EXERCISE = 'exercise';
    const TYPE_VIDEO = 'video';

    public function results()
    {
        return $this->hasOne(Result::class)->where('user_id', auth()->user()->id)->orderByDesc('id');
    }

    public function allResults($user_id = 0)
    {
        if($user_id)
            return $this->hasOne(Result::class)->where('user_id', $user_id)->orderByDesc('id');
        else
            return $this->hasMany(Result::class)->orderByDesc('id');
    }

    public function getNotStartedQuestions()
    {
        if($this->results()->finished()->exists())
            return [];
        if($this->type == 'test') {
            $result = $this->results()->tested()->first();
            $ret_questions = [];
            if ($this->questions) {
                foreach ($this->questions['questions'] as $i => $question) {
                    if ($result && isset($result->answers) && isset($result->answers[$i]))
                        continue;
                    $ret_questions[$i] = $question;
                }
            }
            return $ret_questions;
        }
        return [$this->questions];
    }

    public function positions($user = null)
    {
        if($user) {
            $ret = $this->belongsToMany(Position::class, 't_position_tasks')->using(PositionTaskPivot::class)->whereHas('users', function ($q) use ($user) {
                $q->where('id', $user->id);
            });
            return $ret ;
        }

        return $this->belongsToMany(Position::class,'t_position_tasks')->using(PositionTaskPivot::class);

    }

    public function getAllUser()
    {
        $users = collect();
        foreach ($this->positions as $position)
        {
            foreach ($position->users as $user)
            {
                $users->push($user);

            }
        }
        return $users->unique('id');
    }

    public static function sendUpdateTask($tasks)
    {
        foreach ($tasks->first()->getAllUser() as $user) {
            $update_tasks = collect();
            foreach ($tasks as $task){
                if(!Result::where(['user_id'=>$user->id, 'task_id'=>$task->id])->exists()) {
                    $update_tasks->push($task);
                }
            }

            if($update_tasks->count()) {
                $message = new UpdateTask($user, $update_tasks);
                Mail::to($user->email)->queue($message);
            }
        }
    }

}

