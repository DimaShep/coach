<?php namespace Shep\Coach\Http\Controllers;


use App\User;
use Illuminate\Http\Request;
use Shep\Coach\Facades\Coach;
use Illuminate\Routing\Controller;
use Shep\Coach\Models\Position;
use Shep\Coach\Traits\Avatar;

/**
 * The PositionController class.
 *
 * @package Shep\Coach
 * @author  Dmitriy <dmitriy.shepelenko@gmail.com>
 */

class MapController extends CoachBaseController
{
    public function index(Request $request)
    {
        $positions = Position::orderBy('name')->get();

        return Coach::view(null, $this, 'browse' , compact('positions', 'users'));
    }

    public function show(Request $request, $id)
    {
        $position = Position::find($id);

        return Coach::view(null, $this, 'show' , compact('position', 'users'));
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
        $ret->mentors()->sync($request->get('mentors'));
        return $this->returnUpdate($request, $id, $ret);
    }


}
