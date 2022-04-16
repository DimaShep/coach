<?php

namespace Shep\Coach\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Shep\Coach\Mail\UpdateTask;

class Task extends Model
{
    use \Shep\Coach\Traits\Models\Funcs;

    protected $table = 't_tasks';
    protected $fillable = ['name', 'type', 'text', 'questions', 'info', 'time', 'penalty'];
    protected $casts = [
        'info' => 'array',
        'questions' => 'array'
    ];

    const TYPE_TEST = 'test';
    const TYPE_EXERCISE = 'exercise';
    const TYPE_VIDEO = 'video';
    const TYPE_LESSON = 'lesson';


    public function results($user_id = 0)
    {
        if(!$user_id)
            $user_id = auth()->user()->id;
        return $this->hasOne(Result::class)->where('user_id', $user_id)->orderByDesc('id');
    }

    public function parent()
    {
        if(!$this->pivot->parent_id)
            return null;
        return Task::find($this->pivot->parent_id);
    }

    public function allResults()
    {
        return $this->hasMany(Result::class)->orderByDesc('id');
    }

    public function getPogress()
    {
        $users = $this->getAllUser()->count();
        if(!$users)
            return 0;
        $results = $this->allResults()->where('status', Result::STATUS_FINISHED_OK)->groupBy('user_id')->count();

        return round($results*100/$users);
    }

    public function getMiddleProcent()
    {
        $results = $this->allResults()->where('status', Result::STATUS_FINISHED_OK)->groupBy('user_id')->get();
        if(!$results->count())
            return 0;
        $ret = 0;
        foreach ($results as $result){
            $penalty = Result::where(['task_id'=>$result->task_id, 'user_id'=>$result->user])->sum('penalty');
            $ret += $result->result-$penalty;
        }


        return round($ret/$results->count());
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

    public function deleteTask()
    {
        foreach ($this->allResults as $result){
            $result->deleteResult();
        }
        $this->positions()->detach();
        $path = config('coach.upload.base_path', 'coach/').'tasks/'.$this->id."/";
        Storage::disk('public')->deleteDirectory($path, true);
    }
}

