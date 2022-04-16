<?php namespace Shep\Coach\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

/**
 * The CoachMiddleware class.
 *
 * @package Shep\Coach
 * @author  Dmitriy <dmitriy.shepelenko@gmail.com>
 */
class CoachMiddleware
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!Auth::check())
            return redirect()->route('login');
        if(!Auth()->user()->positions()->exists())
            abort(404);

        return $next($request);
    }
}
