<?php namespace Shep\Coach\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * The CoachModel class.
 *
 * @package Shep\Coach
 * @author  Dmitriy <dmitriy.shepelenko@gmail.com>
 */
class CoachModel extends Model
{
    /**
    * Table name.
    *
    * @var string
    */
    protected $table = 'coach';

    /**
    * The attributes that are mass assignable.
    *
    * @var mixed
    */
    protected $fillable = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];
}
