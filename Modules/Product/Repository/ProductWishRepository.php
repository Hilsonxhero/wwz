<?php

namespace Modules\Product\Repository;

use App\Services\ApiService;
use Modules\Product\Entities\Wish;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class ProductWishRepository implements ProductWishRepositoryInterface
{

    public function get($user)
    {
        return Wish::orderBy('created_at', 'desc')
            ->where('user_id', $user)
            ->paginate(15);
    }

    public function create($data)
    {
        $wish =  Wish::query()->create($data);
        return $wish;
    }

    public function find($id)
    {
        try {
            $wish = Wish::query()->where('id', $id)->firstOrFail();
            return $wish;
        } catch (ModelNotFoundException $e) {
            return  ApiService::_response(trans('response.responses.404'), 404);
        }
    }
    public function delete($id)
    {
        $wish = $this->find($id);
        return $wish->delete();
    }
}
