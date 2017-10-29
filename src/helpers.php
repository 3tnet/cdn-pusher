<?php

if (!function_exists('cdn')) {

    function cdn($path)
    {
        $useCdn = (bool)config('cdn.use_cdn');
        if ($useCdn) {
            return Storage::cloud()->url($path);
        } else {
            return asset($path);
        }
    }
}
