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

    public function allActive()
    {
        return Shipment::orderBy('created_at', 'desc')
            ->where('status', Shipment::ENABLE_STATUS)
            ->with('parent')
            ->paginate();
    }


    public function create($data)
    {
        $shipment =  Shipment::query()->create($data);
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
