<?php

namespace Modules\Shipment\Repository;

use App\Services\ApiService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Shipment\Entities\Shipment;
use Modules\Shipment\Entities\ShipmentDate;
use Modules\Shipment\Entities\ShipmentInterval;

class ShipmentIntervalRepository implements ShipmentIntervalRepositoryInterface
{
    public function all()
    {
        return ShipmentDate::query()->paginate();
    }

    public function get($shipment_date)
    {
        return $shipment_date->intervals()->paginate();
    }

    public function create($data)
    {

        $item =  ShipmentInterval::query()->create([
            'shipment_date_id' =>  $data->input('shipment_date_id'),
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
            'shipment_date_id' =>  $data->input('shipment_date_id'),
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
            $interval = ShipmentInterval::query()->where('id', $id)->firstOrFail();
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
