<?php namespace Shep\Coach\Http\Controllers;


use App\Mail\User\ConfirmOrderMail;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Shep\Coach\Facades\Coach;
use Illuminate\Routing\Controller;
use Shep\Coach\Mail\AddNewUser;
use Shep\Coach\Mail\AddPosition;
use Shep\Coach\Mail\DelPosition;
use Shep\Coach\Models\Position;
use Shep\Coach\Models\PositionUserPivot;
use Shep\Coach\Traits\Avatar;

/**
 * The PositionController class.
 *
 * @package Shep\Coach
 * @author  Dmitriy <dmitriy.shepelenko@gmail.com>
 */

class UserController extends CoachBaseController
{
    public function index(Request $request)
    {
        $data = User::coach()->with(['positions', 'comments'])->get();
        $positions = Position::active()->orderBy('name')->get();
        return Coach::view(null, $this, 'browse' , compact('data', 'positions'));
    }

    public function edit(Request $request, $id)
    {
        $data = User::coach()->where('id', $id)->with('positions')->first();
        $users = User::students()->noBlocked()->noCoach()->get();
        $positions =Position::all();
        $comments = $data->comments;
        return Coach::view(null, $this, 'edit-add', compact('data', 'users', 'positions', 'comments'));
    }

    public function update(Request $request, $id)
    {
        $user = User::find($request->get('user_id'));

        $comment = $request->get('comment');
        if($comment && $comment != ''){
            $user->comments()->create(['comment'=>$comment]);
        }

        $position = clone $user->positions()->get();
        $user->positions()->sync($request->get('positions'));
        $new_position = clone $user->positions()->get();

        $add = $new_position->diff($position);
        $del  = $position->diff($new_position);
        $message = null;
        if($add->count())
            $message = new AddPosition($user, $add);
        else if($del->count())
            $message = new DelPosition($user, $del);
        if($message)
            Mail::to($user->email)->queue($message);

        return $this->returnUpdate($request, $id, $user);
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


        $res =  $data->positions()->detach();
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
