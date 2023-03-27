<?php

namespace Modules\Seller\Repository;

use App\Services\ApiService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use Modules\Seller\Entities\Seller;

class SellerRepository implements SellerRepositoryInterface
{

    public function all()
    {
        return Seller::orderBy('created_at', 'desc')
            ->paginate();
    }

    public function create($data)
    {
        $seller =  Seller::query()->create($data);
        return $seller;
    }
    public function update($id, $data)
    {
        $seller = $this->find($id);
        $seller->update($data);
        return $seller;
    }
    public function show($id)
    {
        $seller = $this->find($id);
        return $seller;
    }

    public function default()
    {
        $seller = $seller = Seller::query()->where('is_default', true)->first();
        return $seller;
    }

    public function find($id)
    {
        try {
            $seller = Seller::query()->where('id', $id)->firstOrFail();
            return $seller;
        } catch (ModelNotFoundException $e) {
            return  ApiService::_response(trans('response.responses.404'), 404);
        }
    }
    public function delete($id)
    {
        $seller = $this->find($id);
        $seller->delete();
    }
}
