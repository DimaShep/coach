<?php namespace Shep\Coach\Http\Controllers;


use App\Jobs\SendUserSocket;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Shep\Coach\Facades\Coach;
use Illuminate\Routing\Controller;
use Shep\Coach\Mail\CheckTask;
use Shep\Coach\Mail\ResetTask;
use Shep\Coach\Mail\UpdateTask;
use Shep\Coach\Models\Position;
use Shep\Coach\Models\PositionTaskPivot;
use Shep\Coach\Models\Result;
use Shep\Coach\Models\Task;
use Shep\Coach\Traits\Avatar;

/**
 * The PositionController class.
 *
 * @package Shep\Coach
 * @author  Dmitriy <dmitriy.shepelenko@gmail.com>
 */

class ApiController extends Controller
{

    public function taskCreate(Request $request, Position  $position)
    {
        $task = Task::create(['name'=>'New Task', 'penalty'=>1, 'info'=>['text'=>0,'img'=>0,'video'=>0]]);
        $data = ['x' => 100, 'y' => 100, 'r' => 75];
        $position->tasks()->attach($task, ['data'=>$data]);
        return ['status'=>'success','tasks'=>$position->data($task->id)->get(), 'position'=>$position];
    }

    public function taskAll(Request $request, Position  $position)
    {
        $user_id = $request->get('user_id', 0);
        $tasks = $position->data()->with('results')->get();
        $results = [];
        foreach ($tasks as $task){
            $results[$task->id] = 0;
            $res_parent = !$task->pivot->parent_id?null:$task->parent()->results($user_id)->orderByDesc('id')->first()->status;
            if($user_id) {
                $res = $task->results($user_id)->orderByDesc('id')->first();
                if ($res) {
                    $results[$task->id] = $res->status;
                }
                else {

                    if (!$task->pivot->parent_id || $res_parent && in_array($res_parent, [Result::STATUS_FINISHED_OK, Result::STATUS_CHECKED])) {
                        $results[$task->id] = Result::STATUS_NEW;
                    }
                }
            }
            else if(!$task->pivot->parent_id || $res_parent && in_array($res_parent,[Result::STATUS_FINISHED_OK, Result::STATUS_CHECKED])){
                $results[$task->id] = Result::STATUS_NEW;
            }
        }
        $progress = null;
        if($user_id) {
            $progress = auth()->user()->getProcPositions();
        }

        return ['status'=>'success','tasks'=> $tasks, 'results' => $results, 'position'=>$position, 'progress'=>$progress];
    }

    public function taskUpdate(Request $request, Position  $position)
    {
        $task_id = $request->get('task_id');
        $data = $request->get('data');
        $parent_id  = $request->get('parent_id', 0);
        $position->tasks()->updateExistingPivot($task_id, ['data'=>$data, 'parent_id'=>$parent_id]);
        return ['status'=>'success', 'message'=>__('coach::message.taskUpdate'), 'tasks'=> $position->data($task_id)->get(), 'position'=>$position];
    }

    public function taskLineDelete(Request $request, Position  $position)
    {
        $task_id = $request->get('task_id');
        $task = $position->data($task_id)->first();
        //$task->save();
        $position->tasks()->updateExistingPivot($task_id, ['parent_id'=>0]);
        return ['status'=>'success', 'message'=>__('coach::message.taskLineDelete'), 'tasks'=> $position->data($task_id)->get(), 'position'=>$position];
    }

    public function taskDelete(Request $request, Position  $position)
    {
        $task_id = $request->get('task_id');
        $task = $position->data($task_id)->first();
        $position->tasks()->detach( $task->id);
        $position->tasks()->where('parent_id', $task->id)->update(['parent_id'=>0]);
        if($task->text == null && $task->type == null)
            $task->delete();
        return ['status'=>'success', 'message'=>__('coach::message.taskDelete'), 'task_id'=> $task_id, 'position'=>$position];

    }

    public function sendAnswerTest(Request $request, Task $task, Position $position)
    {
        $finished = false;
        $question_id = $request->get('question_id', 0);
        $answer = $request->get('answer');
        $user = auth()->user();
        $result = $task->results($user->id)->where('status', Result::STATUS_NEW)->first();

        if (!$result || $result->count() == 0) {
            $result = $user->result()->create(['task_id' => $task->id]);
        }

        if ($task->type == Task::TYPE_TEST){
            $answers = $result->answers ?? [];
            $answers[$question_id]['answer'] = $answer;
            $answers[$question_id]['correct'] = $task->questions['questions'][$question_id]['correct_answer'] == $answer ? true : false;
            $result->time +=  $answers[$question_id]['time'] = strtotime(now()) - strtotime($request->get('start_question'));

        }
        else if ($task->type == Task::TYPE_LESSON){
            $result->time +=  $answers['time'] = strtotime(now())-strtotime($request->get('start_question'));
        }
        else {
            $answers['answer'] = $answer;
            $answers['correct'] = array_fill(0,count($task->questions['points']),0);
            $result->time +=  $answers['time'] = strtotime(now())-strtotime($request->get('start_question'));
        }

        if($task->type == Task::TYPE_VIDEO && $request->hasFile('video')){
            $file = $request->file('video');
            $extension = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
            $file_name = "video_".$result->id.".".$extension;
            $path = Storage::disk('public')->putFileAs('coach/result', $file , $file_name);
            $answers['path'] = $path;
        }

        $result->answers = $answers;

        if($task->type == Task::TYPE_TEST) {
            if (count($task->questions['questions']) == count($result->answers)) {
                $finished = true;
                //$result->time = $result->time = now()->diffInSeconds($result->created_at);
//                $count_answer = $task->results($user->id)->count();
                $count_question = count($task->questions['questions']);
                $count_question_correct = collect($answers)->where('correct', true)->count();
                $proc = round($count_question_correct * 100 / $count_question, 1);
                $result->result = $proc;
                if ($result->result >= $task->questions['proc']) {
                    $result->status = Result::STATUS_FINISHED_OK;
                } else {
                    $result->status = Result::STATUS_FINISHED_FILED;
                    $result->penalty = $task->penalty;
                }

            }
            $result->save();
            dispatch(new SendUserSocket(['rating' => $result->user->getRating()], $result->user_id, 'coachUpdateRating'));

        }
        else if ($task->type == Task::TYPE_LESSON){
            $finished = true;
            //$result->time = now()->diffInSeconds($result->created_at);
            $result->status = Result::STATUS_FINISHED_OK;
            $result->result = 100;
            $result->save();
            dispatch(new SendUserSocket(['rating' => $result->user->getRating()], $result->user_id, 'coachUpdateRating'));
        }
        else{
            $finished = true;
            //$result->time = now()->diffInSeconds($result->created_at);
            $result->status = Result::STATUS_CHECKED;

            $result->save();

            $message = new CheckTask($user, $result);
            foreach ($position->mentors as $mentor) {
                Mail::to($mentor->email)->queue($message);
                dispatch(new SendUserSocket(Result::resultChecks($mentor->id)->select('id')->get()->toArray(), $mentor->id, 'coachMentorResultUpdate'));
            }

        }
        dispatch(new SendUserSocket(['task_id'=>$result->task_id], $result->user_id, 'coachTaskUpdate'));


        $ret = ['question_id' => $question_id, 'finished' => $finished, 'status_task'=>$result->status];
        if($task->type == Task::TYPE_TEST) {
            if ($task->questions['questions'][$question_id]['correct_answer'] == $answer) {
                $ret['status'] = 'success';
                $ret['message'] = __('coach::message.answer_correct');
            } else {
                $ret['status'] = 'wrong';
                $ret['message'] = __('coach::message.answer_wrong');
            }
            $ret['result'] = $result->result.'%';
        }
        else if ($task->type == Task::TYPE_LESSON){
            $ret['status'] = 'success';
            $ret['status_task'] .= '_lesson';

            //$ret['result'] = $ret['message'] = __('coach::message.task_ok');
        }
        else{
            $ret['status'] = 'success';
            $ret['result'] = $ret['message'] = __('coach::message.task_checked');
        }
        return $ret ;
    }

    function positionCopy(Request $request, Position $position)
    {
        $copy_position = Position::find($request->get('position'));
        $tasks = collect();
        foreach ($copy_position->data as $task) {
            if(!$position->tasks()->where('id', $task->id)->exists())
            {
                $position->tasks()->attach($task, ['parent_id'=>$task->pivot->parent_id,'data'=>$task->pivot->data]);
                $tasks->push($task);
            }
        }

        if($tasks->count()) {
            $tasks = $tasks->unique('id');
            Task::sendUpdateTask($tasks);
        }
        return ['status'=>'success', 'message'=>__('coach::message.task_copy')];
    }

    function taskCopy(Request $request, Position $position)
    {
        $task = Task::find($request->get('task'));
        $data = ['x' => 100, 'y' => 100, 'r' => 75];
        $position->tasks()->attach($task, ['data'=>$data]);

        Task::sendUpdateTask(collect()->push($task));
        return ['status'=>'success', 'message'=>__('coach::message.task_copy')];
    }

    function getCopyData(Position $position)
    {
        $copy_positions = [];
        foreach (Position::where('id','!=',$position->id)->get() as $pos)
        {
            $copy_positions[] = $pos;
        }

        $copy_tasks = [];
        foreach (Task::whereNotIn('id',$position->tasks()->pluck('id'))->get() as $task)
        {
            $copy_tasks[] = $task;
        }

        return ['status'=>'success', 'copy_positions'=>$copy_positions, 'copy_tasks'=>$copy_tasks];
    }
}