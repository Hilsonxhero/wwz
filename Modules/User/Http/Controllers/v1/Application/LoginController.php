<?php

namespace Modules\User\Http\Controllers\v1\Application;

use App\Services\ApiService;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\User\Entities\User;
use Modules\User\Services\VerifyCodeService;
use Illuminate\Support\Facades\Http;

class LoginController extends Controller
{

    public function otp(Request $request)
    {
        //      ApiService::_success(config('services.passport.client_secret'));
        $phone = $request->input('username');

        $code = $request->input('code');

        //        $status = VerifyCodeService::check($phone, $code);
        //
        //        if (!$status) {
        //            ApiService::_throw("invalid credentials", 200);
        //        }

        $user = User::where('phone', $phone)->first();

        if (!$user) {
            $user = User::create([
                'phone' => $phone,
            ]);
        }

        try {
            $response = Http::asForm()->post(config('services.passport.login_endpoint'), [
                'grant_type' => 'password',
                'client_id' => config('services.passport.client_id'),
                'client_secret' => config('services.passport.client_secret'),
                'username' => "09224729521",
                'password' => "09224729521",
            ]);

            $data = json_decode($response->getBody());

            return   response()->json([
                // 'user' => auth()->user(),
                'access_token' => $data->access_token,
                'expires_in' => $data->expires_in,
                'refresh_token' => $data->refresh_token,
                'token_type' => $data->token_type,
                "success" => true
            ], 200);
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            if ($e->getCode() === 400) {
                ApiService::_response("Invalid Request. Please enter a username or a password.", $e->getCode());
            } else if ($e->getCode() === 401) {
                ApiService::_response($e->getMessage(), 401);

                ApiService::_response("Your credentials are incorrect. Please try again", $e->getCode());
            }
            ApiService::_response("Something went wrong on the server.", $e->getCode());
        }
    }
}
