<?php namespace Shep\Coach\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * The Coach facade.
 *
 * @package Shep\Coach
 * @author  Dmitriy <dmitriy.shepelenko@gmail.com>
 */
class Coach extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'coach';
    }
}
