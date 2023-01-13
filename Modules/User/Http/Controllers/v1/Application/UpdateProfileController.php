<?php

namespace Modules\User\Http\Controllers\v1\Application;

use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\User\Services\VerifyCodeService;
use Modules\User\Repository\UserRepositoryInterface;
use Illuminate\Support\Facades\Validator;

class UpdateProfileController extends Controller
{

    public $userRepo;
    public function __construct(UserRepositoryInterface $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    /**
     * Update the user username.
     * @param Request $request
     * @return Response
     */
    public function username(Request $request)
    {
        ApiService::Validator($request->all(), [
            'username' => ['required']
        ]);

        $user = auth()->user();

        $this->userRepo->update($user->id, ['username' => $request->username]);

        ApiService::_success(trans('response.responses.200'));
    }

    /**
     * Update the user email.
     * @param Request $request
     * @return Response
     */
    public function email(Request $request)
    {
        ApiService::Validator($request->all(), [
            'email' => ['required']
        ]);

        $user = auth()->user();

        $this->userRepo->update($user->id, ['email' => $request->email]);

        ApiService::_success(trans('response.responses.200'));
    }

    /**
     * Update the user password.
     * @param Request $request
     * @return Response
     */
    public function password(Request $request)
    {
        $user = auth()->user();

        ApiService::Validator($request->all(), [
            'current_password' => [Rule::requiredIf(fn() => $user->has_password)],
            'password' => ['required', 'confirmed'],
        ]);

        $password = Hash::make($request->password);

        if ($user->has_password) {
            $exists = Hash::check($request->current_password, $user->password);
            if (!$exists) {
                ApiService::_withError('current_password', 'رمز عبور فعلی اشتباه می باشد');
            }
        }

        $this->userRepo->update($user->id, ['password' => $password]);

        ApiService::_success(trans('response.responses.200'));
    }


    /**
     * Update the user mobile.
     * @param Request $request
     * @return Response
     */
    public function mobileRequest(Request $request)
    {
        ApiService::Validator($request->all(), [
            'phone' => ['required', 'unique:users,phone']
        ]);

        $user = auth()->user();

        $phone = $request->input('phone');

        if ($user->phone == $phone) {
            ApiService::_success(trans("response.responses.200"));
        }

        $has_exists = VerifyCodeService::has($phone);

        if (!$has_exists) {
            $code = VerifyCodeService::generate();
            VerifyCodeService::destroy($phone);
            $ttl = VerifyCodeService::store($phone, $code);
        } else {
            $ttl = $has_exists;
            $code = $has_exists->code;
        }

        ApiService::_success([
            'phone' => $phone,
            'ttl' => Carbon::parse($ttl->ttl)->DiffInSeconds(now())
        ]);


        // $user->update(['phone' => $request->phone]);

        ApiService::_success(trans('response.responses.200'));
    }

    /**
     * Verify the user mobile.
     * @param Request $request
     * @return Response
     */
    public function mobileVerify(Request $request)
    {
        ApiService::Validator($request->all(), [
            'phone' => ['required', 'unique:users,phone'],
            'code' => ['required']
        ]);

        $user = auth()->user();

        $phone = $request->input('phone');

        $code = $request->input('code');

        $status = VerifyCodeService::check($phone, $code);

        if (!$status) {
            ApiService::_throw(trans('response.auth.invalid_code'), 200);
        }

        $this->userRepo->update($user->id, ['phone' => $phone]);

        ApiService::_success(trans('response.responses.200'));
    }
}