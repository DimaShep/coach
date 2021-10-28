<?php namespace Shep\Coach\Http\Controllers;


use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Shep\Coach\Facades\Coach;
use Illuminate\Routing\Controller;
use Shep\Coach\Mail\CheckTask;
use Shep\Coach\Mail\MentorsResult;
use Shep\Coach\Models\Position;
use Shep\Coach\Models\PositionUserPivot;
use Shep\Coach\Models\Result;
use Shep\Coach\Traits\Avatar;

/**
 * The PositionController class.
 *
 * @package Shep\Coach
 * @author  Dmitriy <dmitriy.shepelenko@gmail.com>
 */

class MentorsController extends CoachBaseController
{
    public function index(Request $request)
    {
//        $query = auth()->user()->mentorsPositions()->whereHas('tasks', function($q){
//            $q->whereHas('results', function ($q) {
//                $q->where('status', Result::STATUS_CHECKED);
//            });
//        })->with('tasks.results');


        $query = Result::where('status', Result::STATUS_CHECKED)->whereHas('task', function ($q) {
            $q->whereHas('positions', function ($q){
                $q->whereHas('mentors', function ($q) {
                    $q->where('id', auth()->user()->id);
                });
            });
        })->with(['user','task.positions']);

        $results = $query->get();


        return Coach::view(null, $this, 'browse' , compact('results'));
    }

    public function checked(Request $request, Result $result)
    {
        return Coach::view(null, $this, 'checked-answer' , compact('result'));
    }

    public function update(Request $request, $id)
    {
        $result = Result::find($id);

        $result->comment = $request->get('comment');
        $result->result = $request->get('result');
        if($result->task->questions['proc'] >  $result->result)
        {
            $result->status = Result::STATUS_FINISHED_FILED;
        }
        else{
            $result->status = Result::STATUS_FINISHED_OK;
        }
        if($result->task->penalty)
            $result->result = $result->result - (($result->howManyAttempts()-1) * $result->task->penalty);
        $ret_points =  $request->get('points');
        $answers = $result->answers ;
        $answers['correct'] = [];
        $answers['points'] = [];
        foreach ($result->task->questions['points']  as $i => $point)
        {
            $answers['correct'][$i] = isset($ret_points[$i])?1:0;
            $answers['points'][$i] = $point;
        }
        $result->answers =$answers;
        $result->save();

        if($request->has('back')){
            $this->setBack($request->get('back'));
        }

        $message = new MentorsResult($result->user, $result);
        Mail::to($result->user->email)->sendNow($message);

        return $this->returnUpdate($request, $id, $result);
    }

}
