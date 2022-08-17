<?php

namespace Modules\Slide\Repository;

use App\Services\ApiService;
use Modules\Slide\Entities\Slide;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class SlideRepository implements SlideRepositoryInterface
{

    public function all()
    {
        return Slide::orderBy('created_at', 'desc')
            ->paginate();
    }

    public function allActive()
    {
        return Slide::orderBy('created_at', 'desc')
            ->where('status', Slide::ENABLE_STATUS)

            ->paginate();
    }


    public function create($data)
    {
        $shipment =  Slide::query()->create($data);
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
            $shipment = Slide::query()->where('id', $id)->firstOrFail();
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
