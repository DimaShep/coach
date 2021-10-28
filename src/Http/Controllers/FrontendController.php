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

        $positions = auth()->user()->positions;
        $counts = [];
        foreach ($positions as $position) {
            $counts['all'][$position->id] = $position->howManyTasks();
            $counts['finish'][$position->id] = $position->howManyTasks(Result::STATUS_FINISHED_OK);
            $counts['checked'][$position->id] = $position->howManyTasks(Result::STATUS_CHECKED);
            $counts['proc_finish'][$position->id] = round($counts['finish'][$position->id] * 100 / $counts['all'][$position->id]);
            $counts['proc_checked'][$position->id] = round($counts['checked'][$position->id] * 100 / $counts['all'][$position->id]);

        }

        $user = auth()->user();
        $q = $user->getRating();
        return Coach::view('coach::frontend.browse', null, null , compact('positions', 'user', 'counts'));
    }


    public function map(Request $request, Position $position)
    {
        $user = auth()->user();
        return Coach::view('coach::frontend.map', null, null , compact('position', 'user'));
    }

    public function task(Request $request, Position $position, Task $task)
    {
        $user = auth()->user();
        return Coach::view('coach::frontend.task', null, null , compact('task',  'position', 'user'));
    }


}
