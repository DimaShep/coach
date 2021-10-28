<?php

namespace Shep\Coach\Mail;



use App\Models\Checkout\Checkout;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Shep\Coach\Models\Task;

class ResetTask extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $task;

    public function __construct(User $user, Task $tasks)
    {
        $this->task = $tasks;
        $this->user = $user;
    }

    public function build()
    {
        $position = $this->task->positions( $this->user)->first();
        $view = $this->subject(__('coach::emails.reset_task'))->view('coach::emails.reset_task')->with([
            'task' => $this->task,
            'user' => $this->user,
            'url' => route('coach.task',['position'=>$position->id,'task'=>$this->task->id]),
        ]);
        return $view;
    }

}