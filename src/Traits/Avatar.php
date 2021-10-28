<?php
namespace Shep\Coach\Traits;

use Illuminate\Support\Facades\Storage;
use Shep\Coach\Coach;

trait Avatar
{
    static public function getPositionsAvatars()
    {

        $files = Storage::disk('public')->files('coach/avatars');
        $urls = Coach::image($files[0]);

        return $files;
    }
}