<?php

namespace Modules\Shipment\Repository;

use App\Services\ApiService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Shipment\Entities\Shipment;

class ShipmentRepository implements ShipmentRepositoryInterface
{

    public function all()
    {
        return Shipment::orderBy('created_at', 'desc')
            ->paginate();
    }

    public function get()
    {
        return Shipment::orderBy('created_at', 'desc')
            ->get();
    }

    public function create($data)
    {
        if ($data->filled('is_default')) {
            Shipment::query()->update([
                'is_default' => false,
            ]);
        }
        $shipment =  Shipment::query()->create([
            'title' => $data->input('title'),
            'delivery_id' => $data->input('delivery_type'),
            'description' => $data->input('description'),
            'shipping_cost' => $data->input('shipping_cost'),
            'is_default' => $data->input('is_default'),
        ]);
        return $shipment;
    }
    public function createMany($shipment, $data)
    {
        return $shipment->dates()->createMany($data);
    }


    public function update($id, $data)
    {
        if ($data->filled('is_default')) {
            Shipment::query()->where('is_default', true)->update([
                'is_default' => false,
            ]);
        }
        $shipment = $this->find($id);
        $shipment->update([
            'title' => $data->input('title'),
            'description' => $data->input('description'),
            'shipping_cost' => $data->input('shipping_cost'),
            'is_default' => $data->input('is_default'),
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
            $shipment = Shipment::query()->where('id', $id)->firstOrFail();
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
