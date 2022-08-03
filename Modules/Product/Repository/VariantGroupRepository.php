<?php

namespace Modules\Product\Repository;

use App\Services\ApiService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Product\Entities\Feature;
use Modules\Product\Entities\VariantGroup;

class VariantGroupRepository implements VariantGroupRepositoryInterface
{

    public function all()
    {
        return VariantGroup::orderBy('created_at', 'desc')
            ->with('variants')
            ->paginate();
    }

    public function create($data)
    {
        $feature =  VariantGroup::query()->create($data);
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
        return VariantGroup::select('id', 'title')->whereNot('id', $id)->orderBy('created_at', 'desc')
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
            $feature = VariantGroup::query()->where('id', $id)->firstOrFail();
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
