<?php

namespace Modules\User\Services;

use Carbon\Carbon;
use Modules\User\Entities\SmsCode;

class VerifyCodeService
{
    private static $min = 10000;
    private static $max = 99999;

    public static function generate()
    {
        return random_int(self::$min, self::$max);
    }

    public static function store($phone, $code)
    {

        return SmsCode::query()->create([
            'phone' => $phone,
            'code' => $code,
            'ttl' => now()->addMinutes(2),
            'expired_at' => now()->addMinutes(2)
        ]);
    }

    public static function get($phone, $code)
    {
        return SmsCode::where('phone', $phone)->first()->code;
    }

    public static function has($phone)
    {
        return SmsCode::where('phone', $phone)->where('expired_at', '>', now())->first();
    }

    public static function destroy($phone)
    {
        return SmsCode::where('phone', $phone)->delete();
    }

    public static function check($phone, $code)
    {
        $exists = self::has($phone);
        if ($exists && $exists->code == $code) {
            self::destroy($phone);
            return true;
        }

        return false;
    }
}
