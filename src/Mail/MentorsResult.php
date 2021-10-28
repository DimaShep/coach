<?php

namespace Shep\Coach\Mail;


use App\Models\Checkout\Checkout;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Shep\Coach\Models\Result;
use Shep\Coach\Models\Task;

class MentorsResult extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $result;

    public function __construct(User $user, Result $result)
    {
        $this->result = $result;
        $this->user = $user;
    }

    public function build()
    {
        $position = $this->result->task->positions( $this->user)->first();
        $view = $this->subject(__('coach::emails.mentors_result_title'))->view('coach::emails.mentors_result')->with([
            'task' => $this->result->task,
            'result' => $this->result,
            'user' => $this->user,
            'url' => route('coach.task',['position'=>$position->id,'task'=>$this->result->task->id]),
        ]);
        return $view;
    }

}