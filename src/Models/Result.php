<?php

namespace Shep\Coach\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use \Shep\Coach\Traits\Models\Funcs;

    protected $table = 't_results';
    protected $fillable = ['task_id',
        'user_id',
        'status',
        'answers',
        'result',
        'comment'];
    protected $casts = ['answers' => 'array'];

    const STATUS_NOT_TESTED = 0;
    const STATUS_NEW = 1;
    const STATUS_CHECKED = 2;
    const STATUS_FINISHED_OK = 3;
    const STATUS_FINISHED_FILED = 4;

    public function scopeFinished($query)
    {
        return $query->whereIn('status', [self::STATUS_CHECKED, self::STATUS_FINISHED_OK]);
    }

    public function scopeTested($query)
    {
        return $query->where('status', self::STATUS_NEW);
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getPositionsAttribute()
    {
        $positions = collect();
        foreach ($this->task->positions as $position)
        {
            if($positions->where('id', $position->id)->count() == 0 && $position->users()->where('id', $this->user_id)->exists())
                $positions->push($position);
        }
        return $positions;
    }

    public function howManyAttempts()
    {
        return Result::where(['task_id'=>$this->task_id, 'user_id'=>$this->user_id])->count();
    }

    public function getAnswerTime($answer = 0)
    {
        if($this->task->type == Task::TYPE_EXERCISE || $this->task->type == Task::TYPE_EXERCISE) {
            $second = $this->answers['time'];
        }
        else if($answer) {
            $second =$this->answers[$answer]['time'];
        }
        else {
            $second = collect($this->answers)->sum('time');
        }

        $minutes = (int) ($second / 60);
        $second = $second - $minutes * 60;

        $minutes = str_pad($minutes, 2, 0, STR_PAD_LEFT);
        $second = str_pad($second, 2, 0, STR_PAD_LEFT);
        return $minutes.":".$second;

    }
}

