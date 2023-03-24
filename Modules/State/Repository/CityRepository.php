<?php

namespace Modules\State\Repository;

use App\Services\ApiService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\State\Entities\City;
use Modules\State\Enums\CityStatus;

class CityRepository implements CityRepositoryInterface
{

    public function all()
    {
        return City::orderBy('created_at', 'desc')
            ->paginate();
    }

    public function allActive()
    {
        return City::orderBy('created_at', 'desc')
            ->where('status', CityStatus::Enable->value)
            ->with('state')
            ->paginate();
    }

    public function create($data)
    {
        $shipment =  City::query()->create($data);
        return $shipment;
    }

    public function update($id, $data)
    {
        $shipment = $this->find($id);
        $shipment->update($data);
        return $shipment;
    }

    public function show($id)
    {
        $shipment = $this->find($id);
        return $shipment;
    }

    public function find($id)
    {
        try {
            $shipment = City::query()->where('id', $id)->firstOrFail();
            return $shipment;
        } catch (ModelNotFoundException $e) {
            return  ApiService::_response(trans('response.responses.404'), 404);
        }
    }
    public function delete($id)
    {
        $shipment = $this->find($id);
        $shipment->delete();
    }
}
