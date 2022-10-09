<?php

namespace Modules\Shipment\Repository;

use App\Services\ApiService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Shipment\Entities\Shipment;
use Modules\Shipment\Entities\ShipmentType;
use Modules\Shipment\Entities\ShipmentTypeDate;
use Modules\Shipment\Entities\ShipmentTypeInterval;

class ShipmentIntervalRepository implements ShipmentIntervalRepositoryInterface
{
    public function all()
    {
        return ShipmentTypeDate::query()->paginate();
    }

    public function get($shipment_date)
    {
        return $shipment_date->intervals()->paginate();
    }

    public function create($data)
    {

        $item =  ShipmentTypeInterval::query()->create([
            'shipment_type_date_id' =>  $data->input('shipment_type_date_id'),
            'shipping_cost' =>  $data->input('shipping_cost'),
            'order_capacity' =>  $data->input('order_capacity'),
            'end_at' =>  $data->input('end_at'),
            'start_at' =>  $data->input('start_at'),

        ]);

        return $item;
    }


    public function update($id, $data)
    {
        $interval = $this->find($id);
        $interval->update([
            'shipment_type_date_id' =>  $data->input('shipment_type_date_id'),
            'shipping_cost' =>  $data->input('shipping_cost'),
            'order_capacity' =>  $data->input('order_capacity'),
            'end_at' =>  $data->input('end_at'),
            'start_at' =>  $data->input('start_at'),
        ]);
        return $interval;
    }
    public function show($id)
    {
        $interval = $this->find($id);
        return $interval;
    }

    public function find($id)
    {
        try {
            $interval = ShipmentTypeInterval::query()->where('id', $id)->firstOrFail();
            return $interval;
        } catch (ModelNotFoundException $e) {
            return  ApiService::_response(trans('response.responses.404'), 404);
        }
    }
    public function delete($id)
    {
        $interval = $this->find($id);
        $interval->delete();
    }
}
