<?php

namespace Shep\Coach\Models;

use Illuminate\Database\Eloquent\Model;

class DataType extends Model
{
    protected $table = 't_data_types';
    protected $fillable = ['name','slug','model','controller'];
}
