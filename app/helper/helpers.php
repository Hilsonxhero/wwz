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
