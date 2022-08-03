<?php

namespace Modules\Product\Repository;

use App\Services\ApiService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Product\Entities\Variant;

class VariantRepository implements VariantRepositoryInterface
{

    public function all()
    {
        return Variant::orderBy('created_at', 'desc')
            ->with('group')
            ->paginate();
    }

    public function create($data)
    {
        $feature =  Variant::query()->create($data);
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
        return Variant::select('id', 'title')->whereNot('id', $id)->orderBy('created_at', 'desc')
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
            $feature = Variant::query()->where('id', $id)->with('group')->firstOrFail();
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
