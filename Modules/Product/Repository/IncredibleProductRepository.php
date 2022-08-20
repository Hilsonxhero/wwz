<?php

namespace Modules\Product\Repository;

use App\Services\ApiService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Product\Entities\Feature;
use Modules\Product\Entities\IncredibleProduct;

class IncredibleProductRepository implements IncredibleProductRepositoryInterface
{

    public function all()
    {
        return IncredibleProduct::orderBy('created_at', 'desc')
            ->paginate();
    }


    public function create($data)
    {
        $feature =  IncredibleProduct::query()->create($data);
        return $feature;
    }
    public function update($id, $data)
    {
        $feature = $this->find($id);
        $feature->update($data);
        return $feature;
    }
    public function show($id)
    {
        $feature = $this->find($id);
        return $feature;
    }

    public function select($id)
    {
        return IncredibleProduct::select('id', 'title')->whereNot('id', $id)->orderBy('created_at', 'desc')
            ->get();
    }

    public function values($id)
    {
        $feature = $this->find($id);
        return $feature->values()->orderByDesc('created_at')->with('feature')->paginate();
    }


    public function find($id)
    {
        try {
            $feature = IncredibleProduct::query()->where('id', $id)->firstOrFail();
            return $feature;
        } catch (ModelNotFoundException $e) {
            return  ApiService::_response(trans('response.responses.404'), 404);
        }
    }
    public function delete($id)
    {
        $feature = $this->find($id);
        return $feature->delete();
    }
}
