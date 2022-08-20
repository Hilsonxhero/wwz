<?php

namespace Modules\User\Services;


use Modules\User\Entities\SmsCode;

class VerifyCodeService
{
    private static $min = 100000;
    private static $max = 999999;

    public static function generate()
    {
        return random_int(self::$min, self::$max);
    }

    public static function store($phone, $code)
    {
        SmsCode::query()->create([
            'phone' => $phone,
            'code' => $code,
            'expired_at' => now()->addMinutes(2)
        ]);
    }

    public static function get($phone, $code)
    {
//        return cache()->get(self::$prefix . $id);
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

    public static function getRule()
    {
        return 'required|numeric|between:' . self::$min . ',' . self::$max;
    }

    public static function check($phone, $code)
    {
        if (self::get($phone, $code) != $code) return false;
        self::destroy($phone);
        return true;
    }
}

