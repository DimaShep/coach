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
        $data = User::coach()->with('positions')->get();

        return Coach::view(null, $this, 'browse' , compact('data'));
    }

    public function edit(Request $request, $id)
    {
        $data = User::coach()->where('id', $id)->with('positions')->first();
        $users = User::noCoach()->get();
        $positions =Position::all();
        return Coach::view(null, $this, 'edit-add', compact('data', 'users', 'positions'));
    }

    public function update(Request $request, $id)
    {
        $user = User::find($request->get('user_id'));
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



}
