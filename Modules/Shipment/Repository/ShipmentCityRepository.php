<?php

namespace Modules\Shipment\Repository;

use App\Services\ApiService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Shipment\Entities\Shipment;
use Modules\Shipment\Entities\ShipmentType;
use Modules\Shipment\Entities\ShipmentTypeCity;
use Modules\Shipment\Entities\ShipmentTypeDate;

class ShipmentCityRepository implements ShipmentCityRepositoryInterface
{
    public function all()
    {
        return ShipmentTypeDate::query()->paginate();
    }

    public function get($city)
    {
        return $city->shipments()->paginate();
    }


    public function dates($item)
    {
        return $item->dates()->with(['shipment' => [
            'shipment:id,title'
        ]])->paginate();
    }

    public function create($data)
    {
        $item =  ShipmentTypeCity::query()->create($data);
        return $item;
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
            $shipment = ShipmentTypeCity::query()->where('id', $id)->firstOrFail();
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
