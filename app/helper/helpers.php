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



function base64($file)
{
    if (!preg_match('/^(?:[data]{4}:(text|image|application)\/[a-z]*)/', $file)) {
        return false;
    } else {
        return true;
    }
}
