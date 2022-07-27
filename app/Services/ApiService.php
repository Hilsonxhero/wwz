<?php


namespace App\Services;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Validator;

class ApiService
{
    // private static $file;

    public static function Validator(array $data, array $rule, $return = false)
    {
        $fails = Validator::make($data, $rule);
        if ($fails->fails())
            if ($return)
                return false;
            else
                self::_response($fails->errors(), 422);
        return true;
    }


    public static function _success($data)
    {
        self::_response($data, 200, true);
    }

    public static function _throw(string $message, $code = 401, $success = false)
    {
        self::_response($message, $code, $success);
    }

    /**
     * Handle response.
     *
     * @param  $data
     * @param  $code
     * @param  $success
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */

    public static function _response($data, $code, $success = false)
    {
        // response()->json((new ResponseService($message, $success, $code))->getArray(), $code)->send();
        // exit();

        throw new HttpResponseException(response()->json([
            'success'   => $success,
            // 'message'   => 'Validation errors',
            'data'      => $data
        ], $code));
    }
}
