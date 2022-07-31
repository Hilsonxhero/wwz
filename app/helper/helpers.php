<?php

/**
 * @param $text
 * @param $limit
 * @return string
 */
function truncate($text, $limit = 35): ?string
{
    return \Illuminate\Support\Str::limit($text, $limit, ' ...');
}



function base64($file): ?string
{
    if (base64_encode(base64_decode($file, true)) === $file) return true;
    return false;
}
