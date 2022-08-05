<?php

namespace Modules\Warranty\Repository;

use App\Services\ApiService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Warranty\Entities\Warranty;

class WarrantyRepository implements WarrantyRepositoryInterface
{

    public function all()
    {
        return Warranty::orderBy('created_at', 'desc')
            ->paginate();
    }

    public function allActive()
    {
        return Warranty::orderBy('created_at', 'desc')
            ->where('status', Warranty::ENABLE_STATUS)

            ->paginate();
    }


    public function create($data)
    {
        $warranty =  Warranty::query()->create($data);
        return $warranty;
    }
    public function update($id, $data)
    {
        $warranty = $this->find($id);
        $warranty->update($data);
        return $warranty;
    }
    public function show($id)
    {
        $warranty = $this->find($id);
        return $warranty;
    }

    public function find($id)
    {
        try {
            $warranty = Warranty::query()->where('id', $id)->firstOrFail();
            return $warranty;
        } catch (ModelNotFoundException $e) {
            return  ApiService::_response(trans('response.responses.404'), 404);
        }
    }
    public function delete($id)
    {
        $warranty = $this->find($id);
        $warranty->delete();
    }
}
