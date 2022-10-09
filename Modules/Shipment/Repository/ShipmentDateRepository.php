<?php

namespace Modules\Shipment\Repository;

use App\Services\ApiService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Shipment\Entities\Shipment;
use Modules\Shipment\Entities\ShipmentType;
use Modules\Shipment\Entities\ShipmentTypeDate;

class ShipmentDateRepository implements ShipmentDateRepositoryInterface
{
    public function all()
    {
        return ShipmentTypeDate::query()->paginate();
    }

    public function get($city)
    {
        return $city->dates()->paginate();
    }

    public function create($data)
    {
        $date = createDatetimeFromFormat($data->input('date'), 'Y/m/d');
        $item =  ShipmentTypeDate::query()->create([
            'shipment_type_id' =>  $data->input('shipment_type_id'),
            'city_id' =>  $data->input('city_id'),
            'is_holiday' =>  $data->input('is_holiday'),
            'date' => $date,
        ]);

        return $item;
    }


    public function update($id, $data)
    {
        $date = createDatetimeFromFormat($data->input('date'), 'Y/m/d');
        $shipment = $this->find($id);
        $shipment->update([
            'shipment_type_id' =>  $data->input('shipment_type_id'),
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
            $shipment = ShipmentTypeDate::query()->where('id', $id)->firstOrFail();
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
