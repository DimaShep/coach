<?php

namespace Shep\Coach\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PositionTaskPivot extends Pivot
{
    protected $fillable = [
        'parent_id',
        'data'
    ];
    protected $casts = [
        'data' => 'array'
    ];
}