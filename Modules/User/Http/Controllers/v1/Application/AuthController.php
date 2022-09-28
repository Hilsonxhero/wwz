<?php

namespace Modules\User\Http\Controllers\v1\Application;

use App\Services\ApiService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\User\Entities\User;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Contracts\Support\Renderable;
use Modules\User\Services\VerifyCodeService;
use Modules\User\Transformers\App\ShowUserResource;

class AuthController extends Controller
{

    public function authenticate(Request $request)
    {
        $request->validate([
            'phone' => ['required'],
        ]);

        $phone = $request->input('phone');

        $user = User::query()->wherePhone($phone)->first();

        $has_exists = VerifyCodeService::has($phone);

        if (!$has_exists) {
            $code = VerifyCodeService::generate();
            VerifyCodeService::destroy($phone);
            $ttl = VerifyCodeService::store($phone, $code);
        } else {
            $ttl = $has_exists;
            $code = $has_exists->code;
        }
        //        Notification::send(null, new VerifyPhoneNotification($phone, $code));
        ApiService::_success([
            'phone' => $phone, 'has_account' =>  !!$user,
            'login_method' => 'otp', 'ttl' => Carbon::parse($ttl->ttl)->DiffInSeconds(now())
        ]);
    }

    public function init(Request $request)
    {
        return new ShowUserResource(auth()->user());
    }

    public function logout(Request $request)
    {
        auth()->user()->token()->revoke();
        $cookie = cookie()->forget('access_token');
        Cookie::queue(
            $cookie
        );
        ApiService::_success(trans('response.responses.200'));
    }
}
