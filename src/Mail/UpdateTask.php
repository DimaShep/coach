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


class UpdateTask extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $tasks;

    public function __construct(User $user, $tasks)
    {
        $this->tasks = $tasks;
        $this->user = $user;
    }

    public function build()
    {
        $view = $this->subject(__('coach::emails.update_task'))->view('coach::emails.update_task')->with([
            'tasks' => $this->tasks,
            'user' => $this->user,
        ]);
        return $view;
    }

}