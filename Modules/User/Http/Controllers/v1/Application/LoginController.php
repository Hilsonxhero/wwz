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

        // $status = VerifyCodeService::check($phone, $code);

        // if (!$status) {
        //     ApiService::_throw(trans('response.auth.invalid_code'), 200);
        // }



        $user = User::where('phone', $phone)->first();




        if (!$user) {
            $user = User::create([
                'username' => $phone,
                'phone' => $phone,
            ]);
        }


        try {
            // config('services.passport.login_endpoint')

            $response = Http::asForm()->post("http://localhost/oauth/token", [
                'grant_type' => 'password',
                'client_id' => 2,
                'client_secret' => "7Oe6huGcJOZbcqQtlPNQZc4mJi8hoRNgn0TB21ov",
                'username' => $phone,
                'password' => $phone,
                'scope' => '',
            ]);

            // $response = Http::get("https://jsonplaceholder.typicode.com/posts");

            return "hello";

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