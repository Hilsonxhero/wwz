<?php

namespace Modules\Shipment\Repository;

use App\Services\ApiService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Shipment\Entities\Shipment;
use Modules\Shipment\Entities\Shipment;
use Modules\Shipment\Entities\ShipmentDate;

class ShipmentDateRepository implements ShipmentDateRepositoryInterface
{
    public function all()
    {
        return ShipmentDate::query()->paginate();
    }

    public function get($city)
    {
        return $city->dates()->paginate();
    }

    public function create($data)
    {
        $item =  ShipmentDate::query()->create($data);
        return $item;
    }


    public function update($id, $data)
    {
        $date = createDatetimeFromFormat($data->input('date'), 'Y/m/d');
        $shipment = $this->find($id);
        $shipment->update([
            'shipment_id' =>  $data->input('shipment_id'),
            'city_id' =>  $data->input('city_id'),
            'is_holiday' =>  $data->input('is_holiday'),
            'date' => $date,
        ]);
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
            $shipment = ShipmentDate::query()->where('id', $id)->firstOrFail();
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
