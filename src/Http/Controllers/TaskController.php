<?php namespace Shep\Coach\Http\Controllers;


use App\Jobs\SendUserSocket;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Shep\Coach\Facades\Coach;
use Illuminate\Routing\Controller;
use Shep\Coach\Mail\UpdateTask;
use Shep\Coach\Mail\DelPosition;
use Shep\Coach\Mail\ResetTask;
use Shep\Coach\Models\Position;
use Shep\Coach\Models\Result;
use Shep\Coach\Models\Task;
use Shep\Coach\Traits\Avatar;
use Shep\Coach\Services\MainServices;

/**
 * The PositionController class.
 *
 * @package Shep\Coach
 * @author  Dmitriy <dmitriy.shepelenko@gmail.com>
 */

class TaskController extends CoachBaseController
{
    public function index(Request $request)
    {
        $positions = Position::active()->orderBy('name')->get();
        $data = Task::orderBy('id')->get();
        return Coach::view(null, $this, 'browse' , compact('data', 'positions', 'users'));
    }

    public function edit(Request $request, $id)
    {
        $data = null;
        if($id)
            $data = $this->model->find($id);

        return Coach::view(null, $this, 'edit-add', compact('data','this'));
    }

    public function create(Request $request)
    {
        $data = null;

        $positions = Position::active()->orderBy('name')->get();

        return Coach::view(null, $this, 'edit-add', compact('data','this', 'positions'));
    }

    public function store(Request $request)
    {
        $task = Task::create(['name'=>'New Task', 'penalty'=>1, 'info'=>['text'=>0,'img'=>0,'video'=>0]]);
        $data = ['x' => 100, 'y' => 100, 'r' => 75];
        $position = Position::find($request->get('position'));
        $position->tasks()->attach($task, ['data'=>$data]);

        return $this->update($request, $task->id);
    }
    public function update(Request $request, $id)
    {

        $task = $this->updateOrCreate($request, $id);
        //$task->mentors()->sync($request->get('mentors'));
        $t = $task->info;
        $t['text'] = mb_strlen(str_replace(["\n","\r", "&nbsp;"], ['','',' '], preg_replace('/<[^>]*>/','', $task->text)));
        preg_match_all('(<img)',$task->text,$match);
        $t['img'] = $match?count($match[0]):0;
        preg_match_all('(<iframe)',$task->text,$match);
        $t['video'] = $match?count($match[0]):0;

        $task->update(['info'=>$t, 'text'=>str_replace(["\n","\r", "&nbsp;"], ['','',' '],$task->text)]);
        if(in_array($task->type, [Task::TYPE_EXERCISE, Task::TYPE_VIDEO]))
        {
            $qestions = $task->questions;
            $points = [];
            foreach (explode("\r\n", $qestions['points']) as $point)
            {
                $point = trim($point);
                if(strlen($point)>0)
                    $points[] = $point;
            }
            $qestions['points'] = $points;
            $task->update(['questions'=>$qestions]);

        }
        if($request->has('reset_results')){
            Result::where('task_id', $id)->delete();
            foreach ($task->getAllUser() as $user)
            {
                $message = new ResetTask($user, collect()->push($task));
                Mail::to($user->email)->queue($message);
            }
        }

        if($request->has('email_new_test')){
            Task::sendUpdateTask(collect()->push($task));
        }

        $directory = config('coach.upload.base_path', 'coach/').'tasks/'.$task->id."/";
        $files = Storage::disk('public')->files($directory);
        foreach ($files as $file){
            if(strpos($task->text, $file) === false){
                Storage::disk('public')->delete($file);
            }
        }
        $files = Storage::disk('public')->directories($directory);
        foreach ($files as $file){
            if(strpos($task->text, $file) === false){
                Storage::disk('public')->deleteDirectory($file, true);
            }
        }


        if($request->has('back')){
            $this->setBack($request->get('back'));
        }
        return $this->returnUpdate($request, $id, $task);
    }

    function reset(Request $request)
    {
        $ids = $request->get('ids');
        MainServices::resetTasks($ids);
        return ['ok'];
    }


}
