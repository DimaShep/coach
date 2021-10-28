<?php

namespace Shep\Coach\Models;

use App\User;
use Illuminate\Database\Eloquent\Relations\Pivot;

class PositionUserPivot extends Pivot
{
    protected $table="t_position_users";

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id');
    }
}

