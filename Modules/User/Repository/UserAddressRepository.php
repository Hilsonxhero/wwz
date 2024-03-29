<?php

namespace Modules\User\Repository;


use App\Services\ApiService;
use Modules\User\Entities\User;

use Modules\State\Entities\State;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\User\Entities\Address;

class UserAddressRepository implements UserAddressRepositoryInterface
{

    public function all()
    {
        return auth()->user()->addresses;
    }

    public function create($data)
    {
        $user = auth()->user();

        $user->addresses()->update(['is_default' => false]);

        $address =  Address::query()->create([
            'user_id' => $user->id,
            'state_id' => $data->input('state_id'),
            'city_id' => $data->input('city_id'),
            'address' => $data->input('address'),
            'postal_code' => $data->input('postal_code'),
            'telephone' => $data->input('telephone'),
            'mobile' => $user->phone,
            'is_default' =>  true,
            'latitude' => $data->input('latitude'),
            'longitude' => $data->input('longitude'),
            'building_number' => $data->input('building_number'),
            'unit' => $data->input('unit'),
        ]);
        return $address;
    }

    public function update($id, $data)
    {
        $address = $this->find($id);
        $address->update($data);
        return $address;
    }



    public function show($id)
    {
        $address = $this->find($id);
        return $address;
    }

    public function cities($id)
    {
        $address = $this->find($id);
        return $address->cities()->orderByDesc('created_at')->paginate();
    }

    public function find($id)
    {
        try {
            $address = Address::query()->where('id', $id)->firstOrFail();
            return $address;
        } catch (ModelNotFoundException $e) {
            return  ApiService::_response(trans('response.responses.404'), 404);
        }
    }

    public function delete($id)
    {
        $address = $this->find($id);
        $address->delete();
    }
}
