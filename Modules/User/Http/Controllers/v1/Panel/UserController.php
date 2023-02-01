<?php

namespace Modules\User\Http\Controllers\v1\Panel;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\User\Repository\UserRepositoryInterface;
use Modules\User\Transformers\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    private $userRepo;
    public function __construct(UserRepositoryInterface $userRepo)
    {
        $this->userRepo = $userRepo;
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $users = $this->userRepo->all();
        return UserResource::collection($users);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return Response
     */

    public function select(Request $request)
    {
        $categories = $this->userRepo->select($request->q);
        ApiService::_success($categories);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {

        ApiService::Validator($request->all(), [
            'username' => ['required', 'min:3'],
            'email' => ['required', 'email', 'min:3', 'unique:users,email'],
            'phone' => ['required', 'min:3', 'unique:users,phone'],
            'national_identity_number' => ['required'],
            'password' => ['required', 'confirmed'],
            'status' => ['required'],
        ]);

        $data = [
            'username' => $request->username,
            'email' => $request->email,
            'role' => $request->role,
            'phone' => $request->phone,
            'national_identity_number' => $request->national_identity_number,
            'cart_number' => $request->cart_number,
            'password' => Hash::make($request->password),
        ];
        // ApiService::_response($data['role'], 403);

        $user = $this->userRepo->create($data);

        if ($request->profile) {
            $user->clearMediaCollectionExcept('avatar');
            base64(json_decode($request->profile)) ? $user->addMediaFromBase64(json_decode($request->profile))->toMediaCollection('avatar')
                : $user->addMedia($request->profile)->toMediaCollection('avatar');
        }

        ApiService::_success($user);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $user = $this->userRepo->show($id);
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        ApiService::Validator($request->all(), [
            'username' => ['required', 'min:3'],
            'email' => ['required', 'email', 'min:3', Rule::unique('users', 'email')->ignore($id)],
            'phone' => ['required', 'min:3', Rule::unique('users', 'phone')->ignore($id)],
            'national_identity_number' => ['required'],
            'password' => ['nullable', 'confirmed'],
            'status' => ['required'],
        ]);

        $request->filled('password') ? $request->all() : $request->except(['password']);

        $data = [
            'username' => $request->username,
            'email' => $request->email,
            'role' => $request->role,
            'phone' => $request->phone,
            'national_identity_number' => $request->national_identity_number,
            'cart_number' => $request->cart_number,
            'password' => Hash::make($request->password),
        ];
        // ApiService::_response($data['role'], 403);

        $user = $this->userRepo->update($id, $data);

        if ($request->profile) {
            $user->clearMediaCollectionExcept('avatar');
            base64(json_decode($request->profile)) ? $user->addMediaFromBase64(json_decode($request->profile))->toMediaCollection('avatar')
                : $user->addMedia($request->profile)->toMediaCollection('avatar');
        }

        ApiService::_success($user);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $user = $this->userRepo->delete($id);
        ApiService::_success(trans('response.responses.200'));
    }
}
