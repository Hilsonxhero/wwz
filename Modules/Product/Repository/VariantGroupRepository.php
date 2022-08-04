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
        $variant =  VariantGroup::query()->create($data);
        return $variant;
    }
    public function update($id, $data)
    {
        $variant = $this->find($id);
        $variant->update($data);
        return $variant;
    }
    public function show($id)
    {
        $variant = $this->find($id);
        return $variant;
    }

    public function select($id)
    {
        return VariantGroup::select('id', 'name')->whereNot('id', $id)->orderBy('created_at', 'desc')
            ->get();
    }

    public function values($id)
    {
        $variant = $this->find($id);
        return $variant->variants()->orderByDesc('created_at')->with('group')->paginate();
    }


    public function find($id)
    {
        try {
            $variant = VariantGroup::query()->where('id', $id)->firstOrFail();
            return $variant;
        } catch (ModelNotFoundException $e) {
            return  ApiService::_response(trans('response.responses.404'), 404);
        }
    }
    public function delete($id)
    {
        $variant = $this->find($id);
        return $variant->delete();
    }
}
