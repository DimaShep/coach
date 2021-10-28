<?php

if (! function_exists('coach')) {
    function coach()
    {
        return 'Welcome to function coach() for Shep\Coach package';
    }
}


if (!function_exists('coach_asset')) {
    function coach_asset($path, $secure = null)
    {
        return asset(config('coach.assets_path').'/'.$path, $secure);
    }
}