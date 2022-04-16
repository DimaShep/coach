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

        $mentors = User::getMentors();

        $avatars = Avatar::getPositionsAvatars();

        return Coach::view(null, $this, 'edit-add', compact('data','this', 'mentors', 'avatars'));
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


    public function getDayToAutoreset()
    {

    }

    public function destroy(Request $request, $id)
    {
        $ids = [];
        if (empty($id)) {
            // Bulk delete, get IDs from POST
            $ids = explode(',', $request->ids);
        } else {
            // Single item delete, get ID from URL
            $ids[] = $id;
        }
        foreach ($ids as $id) {
            $data = call_user_func([$this->model, 'findOrFail'], $id);
        }

        $displayName = count($ids) > 1 ? $this->dataType->name_plural : $this->dataType->name;

        $positions = Position::whereIn('id', $ids)->get();
        foreach ($positions as $position){
            $position->mentors()->detach();
            $position->users()->detach();
            $position->tasks()->detach();
        }

        $res = $data->destroy($ids);
        $data = $res
            ? [
                'message'    => __('coach::message.successfully_deleted')." {$displayName}",
                'alert-type' => 'success',
            ]
            : [
                'message'    => __('coach::message.error_deleting')." {$displayName}",
                'alert-type' => 'error',
            ];

        if ($request->ajax()) {
            return response()->json(['success' => true, 'data' => $data]);
        }

        return redirect()->route("coach.{$this->slug}.index")->with($data);
    }
}
