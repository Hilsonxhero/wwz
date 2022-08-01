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
        $category =  Shipment::query()->create($data);
        return $category;
    }
    public function update($id, $data)
    {
        $category = $this->find($id);
        $category->update($data);
        return $category;
    }
    public function show($id)
    {
        $category = $this->find($id);
        return $category;
    }

    public function find($id)
    {
        try {
            $category = Shipment::query()->where('id', $id)->firstOrFail();
            return $category;
        } catch (ModelNotFoundException $e) {
            return  ApiService::_response(trans('response.responses.404'), 404);
        }
    }
    public function delete($id)
    {
        $category = $this->find($id);
        $category->clearMediaCollectionExcept();
        $category->delete();
    }
}
