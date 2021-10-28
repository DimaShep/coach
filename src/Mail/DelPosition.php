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

class DelPosition extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $positions;


    public function __construct(User $user, $positions)
    {
        $this->user = $user;
        $this->positions = $positions;
    }

    public function build()
    {
        $view = $this->subject(__('coach::emails.add_position'))->view('coach::emails.del_position')->with([
            'positions' => $this->positions,
            'user' => $this->user,
            'url' => route('coach.index'),
        ]);
        return $view;
    }

}