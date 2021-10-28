<?php namespace Shep\Coach\Http\Controllers;


use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Shep\Coach\Facades\Coach;
use Illuminate\Routing\Controller;
use Shep\Coach\Mail\UpdateTask;
use Shep\Coach\Mail\DelPosition;
use Shep\Coach\Mail\ResetTask;
use Shep\Coach\Models\Position;
use Shep\Coach\Models\Result;
use Shep\Coach\Models\Task;
use Shep\Coach\Traits\Avatar;

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
        $positions = Position::orderBy('name')->get();

        return Coach::view(null, $this, 'browse' , compact('positions', 'users'));
    }

    public function edit(Request $request, $id)
    {
        $data = null;
        if($id)
            $data = $this->model->find($id);

        $users = User::all();

        $avatars = Avatar::getPositionsAvatars();

        return Coach::view(null, $this, 'edit-add', compact('data','this', 'users', 'avatars'));
    }

    public function create(Request $request)
    {


    }

    public function update(Request $request, $id)
    {
        $ret = $this->updateOrCreate($request, $id);
        //$ret->mentors()->sync($request->get('mentors'));
        $t = $ret->info;
        $t['text'] = mb_strlen(str_replace(["\n","\r", "&nbsp;"], ['','',' '], preg_replace('/<[^>]*>/','', $ret->text)));
        preg_match_all('(<img)',$ret->text,$match);
        $t['img'] = $match?count($match[0]):0;
        preg_match_all('(<iframe)',$ret->text,$match);
        $t['video'] = $match?count($match[0]):0;

        $ret->update(['info'=>$t, 'text'=>str_replace(["\n","\r", "&nbsp;"], ['','',' '],$ret->text)]);
        if(in_array($ret->type, [Task::TYPE_EXERCISE, Task::TYPE_VIDEO]))
        {
            $qestions = $ret->questions;
            $points = [];
            foreach (explode("\r\n", $qestions['points']) as $point)
            {
                $point = trim($point);
                if(strlen($point)>0)
                    $points[] = $point;
            }
            $qestions['points'] = $points;
            $ret->update(['questions'=>$qestions]);

        }
        if($request->has('reset_results')){
            Result::where('task_id', $id)->delete();
            foreach ($ret->getAllUser() as $user)
            {
                $message = new ResetTask($user, collect()->push($ret));
                Mail::to($user->email)->queue($message);
            }
        }

        if($request->has('email_new_test')){
            Task::sendUpdateTask(collect()->push($ret));
        }

        if($request->has('back')){
            $this->setBack($request->get('back'));
        }
        return $this->returnUpdate($request, $id, $ret);
    }


}
