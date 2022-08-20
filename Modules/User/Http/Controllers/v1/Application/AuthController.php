<?php

namespace Modules\User\Http\Controllers\v1\Application;

use App\Services\ApiService;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\User\Entities\User;
use Modules\User\Services\VerifyCodeService;

class AuthController extends Controller
{

    public function authenticate(Request $request)
    {
        $request->validate([
            'phone' => ['required'],
        ]);

        $phone = $request->input('phone');

        $user = User::query()->wherePhone($phone)->first();

        $code = VerifyCodeService::has($phone);

        if (!$code) {
            $code = VerifyCodeService::generate();
            VerifyCodeService::destroy($phone);
            VerifyCodeService::store($phone, $code);
        } else {
            $code = $code->code;
        }

//        Notification::send(null, new VerifyPhoneNotification($phone, $code));

        ApiService::_success(['phone' => $phone, 'has_account' =>  !!$user, 'login_method' => 'otp']);
    }

}
