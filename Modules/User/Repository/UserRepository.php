<?php

namespace Modules\User\Repository;


use App\Services\ApiService;
use Modules\User\Entities\User;

use Modules\State\Entities\State;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserRepository implements UserRepositoryInterface
{

    public function all()
    {
        return User::orderBy('created_at', 'desc')
            ->with(['city'])
            ->paginate();
    }

    public function cart()
    {
        return  auth()->user()->cart->items()->with('variant')->get();
    }


    public function allActive()
    {
        return User::orderBy('created_at', 'desc')
            ->where('status', User::STATUS_ACTIVE)
            ->with('parent')
            ->paginate();
    }


    public function create($data)
    {
        $user =  User::query()->create($data);
        $user->syncRoles($data['role']);
        return $user;
    }
    public function update($id, $data)
    {
        $user = $this->find($id);
        if (!request()->filled('password')) {
            unset($data['password']);
        } elseif (request()->filled('role')) {
            $user->syncRoles($data['role'])->update($data);
        }
        $user->update($data);
        return $user;
    }
    public function show($id)
    {
        $user = $this->find($id);
        return $user;
    }

    public function cities($id)
    {
        $user = $this->find($id);
        return $user->cities()->orderByDesc('created_at')->paginate();
    }

    public function find($id)
    {
        try {
            $user = User::query()->where('id', $id)->firstOrFail();
            return $user;
        } catch (ModelNotFoundException $e) {
            return  ApiService::_response(trans('response.responses.404'), 404);
        }
    }
    public function findByPhone($phone)
    {
        return User::query()->where('phone', $phone)->first();
    }

    public function delete($id)
    {
        $user = $this->find($id);
        $user->delete();
    }
}
