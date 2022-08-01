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
        $category =  Warranty::query()->create($data);
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
            $category = Warranty::query()->where('id', $id)->firstOrFail();
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
