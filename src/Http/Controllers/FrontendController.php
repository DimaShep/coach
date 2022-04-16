<?php namespace Shep\Coach\Http\Controllers;


use App\User;
use Illuminate\Http\Request;
use Shep\Coach\Facades\Coach;
use Illuminate\Routing\Controller;
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

class FrontendController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $positions = auth()->user()->positions;
        if($positions->count()>0)
            return redirect()->route('coach.map',[$positions->first()]);

        $counts = auth()->user()->getProcPositions();

        $q = $user->getRating();
        return Coach::view('coach::frontend.browse', null, null , compact('positions', 'user', 'counts'));
    }


    public function map(Request $request, Position $position)
    {
        $user = auth()->user();
        $positions = auth()->user()->positions;
        if(!$positions->where('id',$position->id)->count())
            return redirect()->route('coach.map',[$positions->first()]);
        $counts = auth()->user()->getProcPositions();


        return Coach::view('coach::frontend.map', null, null , compact('position','positions', 'user', 'counts'));
    }

    public function task(Request $request, Position $position, Task $task)
    {
        $user = auth()->user();
        return Coach::view('coach::frontend.task', null, null , compact('task',  'position', 'user'));
    }

    public function results(Request $request)
    {
        $user = auth()->user();
        $results = $user->results()->where('status', '!=', Result::STATUS_CHECKED)->orderByDesc('id')->get();
        $counts = auth()->user()->getProcPositions();
        return Coach::view('coach::frontend.results', null, null , compact('results', 'counts'));
    }


}
