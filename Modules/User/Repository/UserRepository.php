<?php

namespace Modules\User\Repository;


use App\Services\ApiService;
use Modules\User\Entities\User;

use Modules\State\Entities\State;
use Modules\Cart\Enums\CartStatus;
use Modules\User\Enums\UserStatus;
use Modules\Order\Enums\OrderStatus;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserRepository implements UserRepositoryInterface
{

    public function all()
    {
        return User::orderBy('created_at', 'desc')
            ->with(['city'])
            ->paginate();
    }

    public function orders($user)
    {

        $query = $user->orders()->with(['shippings' => ['items' => [
            'product' => ['media', 'brand', 'category' => ['media'], 'delivery'],
            'variant' => ['warranty', 'incredible', 'shipment', 'combinations' => ['variant' => ['group']]]
        ], 'shipment'], 'user', 'payment_method', 'payments'])->orderBy('created_at', 'desc');

        if (request()->has('status')) {
            if (request()->status == "sent") {
                $query->where('status', OrderStatus::Sent->value);
            }
            if (request()->status == "canceled") {
                $query->where('status', OrderStatus::CanceledSystem->value);
            }
            if (request()->status == "returned") {
                $query->where('status', OrderStatus::Returned->value);
            }
            if (request()->status == "progress") {
                $query->whereIn('status', [
                    OrderStatus::WaitPayment->value, OrderStatus::Processed->value,
                    OrderStatus::DeliveryDispatcher->value, OrderStatus::Sent->value,
                    OrderStatus::LeavingCenter->value, OrderStatus::ReceivedCenter->value
                ]);
            }
        }

        return $query->paginate(15);
    }


    public function select($q)
    {
        $query =  User::select('id', 'username')->orderBy('created_at', 'desc');

        $query->when(request()->has('q'), function ($query) use ($q) {
            $query->where('username', 'LIKE', "%" . $q . "%");
        });

        return $query->take(25)->get();
    }

    public function cart()
    {
        return  auth()->user()->carts()->where('status', CartStatus::Available->value)
            ->with(['items' => ['product' => ['delivery', 'media'], 'variant' => ['incredible']]])
            ->first();
    }


    public function allActive()
    {
        return User::orderBy('created_at', 'desc')
            ->where('status', UserStatus::ACTIVE)
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
