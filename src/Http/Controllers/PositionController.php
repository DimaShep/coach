<?php namespace Shep\Coach\Http\Controllers;


use App\User;
use Illuminate\Http\Request;
use Shep\Coach\Facades\Coach;
use Illuminate\Routing\Controller;
use Shep\Coach\Models\Position;
use Shep\Coach\Models\Task;
use Shep\Coach\Traits\Avatar;

/**
 * The PositionController class.
 *
 * @package Shep\Coach
 * @author  Dmitriy <dmitriy.shepelenko@gmail.com>
 */

class PositionController extends CoachBaseController
{
    public function index(Request $request)
    {
        $data = Position::orderBy('name')->get();
        $users = User::all();

        return Coach::view(null, $this, 'browse' , compact('data', 'users'));
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

    public function update(Request $request, $id)
    {
        $ret = $this->updateOrCreate($request, $id);
        $data =[];

        $ret->mentors()->sync($request->get('mentors'));
        if($request->has('back')){
            $this->setBack($request->get('back'));
        }
        return $this->returnUpdate($request, $id, $ret);
    }

    public function map(Request $request, Position $position){

        $users = User::all();

        $avatars = Avatar::getPositionsAvatars();


        return Coach::view(null, $this, 'map', compact('position','this', 'users', 'avatars'));
    }

}
