<?php

use Jenssegers\Agent\Agent;


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

function createDatetimeFromFormat($date, $format = 'Y/m/d H:i')
{
    return \Morilog\Jalali\CalendarUtils::createDatetimeFromFormat($format, $date);
}

function formatGregorian($date, $format = 'Y/m/d H:i')
{
    if ($date)  return \Morilog\Jalali\CalendarUtils::strftime($format, strtotime($date, "Y/m/d"));
    return null;
}

function isMobile()
{
    $agent = new Agent();
    $agent->setUserAgent(request()->header('user-agent'));
    return $agent->isPhone() ? true : false;
    // return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|up\.browser|up\.link|webos|wos)/i", request()->header('user-agent'));
}


function isTablet()
{
    $agent = new Agent();
    $agent->setUserAgent(request()->header('user-agent'));
    return $agent->isTablet() ? true : false;
}
