<?php

namespace Modules\User\Http\Controllers\v1\Application;

use GuzzleHttp\Client;
use App\Services\ApiService;
use Illuminate\Http\Request;
use Modules\User\Entities\User;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cookie;
use Modules\User\Services\VerifyCodeService;
use Modules\User\Events\App\UserAuthenticatied;
use Modules\User\Transformers\App\TokenResource;

class LoginController extends Controller
{

    public function otp(Request $request)
    {
        $phone = $request->input('username');

        $code = $request->input('code');

        $status = VerifyCodeService::check($phone, $code);

        if (!$status) {
            ApiService::_throw(trans('response.auth.invalid_code'), 200);
        }

        $user = User::where('phone', $phone)->first();

        if (!$user) {
            $user = User::create([
                'username' => $phone,
                'phone' => $phone,
            ]);
        }

        // return config('services.passport.login_endpoint');

        try {

            $response = Http::asForm()->post(config('services.passport.login_endpoint'), [
                'grant_type' => 'password',
                'client_id' => config('services.passport.client_id'),
                'client_secret' => config('services.passport.client_secret'),
                'username' => $phone,
                'password' => $phone,
                'scope' => '',
            ]);

            $data = json_decode($response->getBody());

            Cookie::queue(
                'access_token',
                $data->access_token,
                45000,
                null,
                null,
                false,
                true,
                false,
                'Strict'
            );


            $request->headers->add([
                'Authorization' => 'Bearer ' . $data->access_token
            ]);

            event(new UserAuthenticatied($user));
            return new TokenResource($data);
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
