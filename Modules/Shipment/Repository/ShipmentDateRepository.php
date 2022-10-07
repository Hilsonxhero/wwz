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
    public function get($shipment)
    {
        return $shipment->dates()->paginate();
    }

    public function create($data)
    {
        $date = createDatetimeFromFormat($data->input('date'), 'Y/m/d');

        

        $item =  ShipmentTypeDate::query()->create([
            'shipment_type_id' =>  $data->input('shipment_type_id'),
            'is_holiday' =>  $data->input('is_holiday'),
            'date' => $date,
            'weekday_name' => "weed"
        ]);

        return $item;
    }
    public function update($id, $data)
    {
        $shipment = $this->find($id);
        $shipment->update([
            'title' => $data->input('title'),
            'description' => $data->input('description'),
            'shipping_cost' => $data->input('shipping_cost'),
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
            $shipment = ShipmentType::query()->where('id', $id)->firstOrFail();
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
