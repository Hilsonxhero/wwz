<?php

namespace Modules\Shipment\Repository;

use App\Services\ApiService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Shipment\Entities\Delivery;
use Modules\Shipment\Entities\Shipment;
use Modules\Shipment\Entities\ShipmentDate;

class DeliveryRepository implements DeliveryRepositoryInterface
{
    public function all()
    {
        return Delivery::query()->orderBy('created_at', 'desc')->paginate();
    }

    public function get()
    {
        return Delivery::query()->orderBy('created_at', 'desc')->get();
    }

    public function create($data)
    {

        $item =  Delivery::query()->create([
            'title' =>  $data->input('title'),
            'code' =>  $data->input('code'),
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
            $shipment = Delivery::query()->where('id', $id)->firstOrFail();
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
