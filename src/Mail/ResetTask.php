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
    public $data_tasks;

    public function __construct(User $user, $data_tasks)
    {
        $this->data_tasks = $data_tasks;
        $this->user = $user;
    }

    public function build()
    {
        $data = [];
        foreach ($this->data_tasks as $task)
        {
            $position = $task->positions->first();
            $data[$task->name] = route('coach.task',['position'=>$position->id,'task'=>$task->id]);
        }

        $view = $this->subject(__('coach::emails.reset_task'))->view('coach::emails.reset_task')->with([
            'tasks' => $data,
            'user' => $this->user,
        ]);
        return $view;
    }

}