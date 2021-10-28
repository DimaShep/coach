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

class CheckTask extends Mailable
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
        $view = $this->subject(__('coach::emails.check_task_title'))->view('coach::emails.check_task')->with([
            'task' => $this->result->task,
            'user' => $this->user,
            'url' => route('coach.mentors.checked',['result'=>$this->result->id]),
        ]);
        return $view;
    }

}