<?php

namespace Modules\Voucher\Repository;

use App\Services\ApiService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Voucher\Entities\Voucher;
use Modules\Voucher\Entities\Voucherable;

class VoucherableRepository implements VoucherableRepositoryInterface
{

    public function all()
    {
        return Voucherable::orderBy('created_at', 'desc')
            ->paginate();
    }

    public function take()
    {
        return Voucherable::with('category')
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();
    }


    public function create($data)
    {
        $voucherable =  Voucherable::query()->create($data);
        return $voucherable;
    }
    public function createMany($data)
    {
        $voucherable =  Voucherable::query()->insert($data);
        return $voucherable;
    }
    public function update($id, $data)
    {
        $voucherable = $this->find($id);

        $voucherable =  Voucherable::query()->update($data);
        return $voucherable;
    }
    public function show($id)
    {
        $voucherable = $this->find($id);
        return $voucherable;
    }

    public function find($id)
    {
        try {
            $voucherable = Voucherable::query()->where('id', $id)->firstOrFail();
            return $voucherable;
        } catch (ModelNotFoundException $e) {
            return  ApiService::_response(trans('response.responses.404'), 404);
        }
    }
    public function delete($id)
    {
        $voucherable = $this->find($id);
        $voucherable->delete();
    }
}
