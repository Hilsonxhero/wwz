<?php

namespace Modules\Payment\Repository;

use App\Services\ApiService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Category\Entities\Category;
use Modules\Payment\Entities\Gateway;
use Modules\Payment\Entities\PaymentMethod;

class GatewayRepository implements GatewayRepositoryInterface
{

    public function all()
    {
        return Gateway::orderBy('created_at', 'desc')
            ->paginate();
    }

    public function allActive()
    {
        return Gateway::orderBy('created_at', 'desc')
            ->where('status', Gateway::ENABLE_STATUS)
            ->paginate();
    }

    public function create($data)
    {
        $method =  Gateway::query()->create($data);
        return $method;
    }

    public function update($id, $data)
    {
        $method = $this->find($id);
        $method->update($data);
        return $method;
    }

    public function show($id)
    {
        $method = $this->find($id);
        return $method;
    }

    public function find($id)
    {
        try {
            $method = Gateway::query()->where('id', $id)->firstOrFail();
            return $method;
        } catch (ModelNotFoundException $e) {
            return  ApiService::_response(trans('response.responses.404'), 404);
        }
    }
    public function delete($id)
    {
        $method = $this->find($id);
        if ($method)   $method->delete();
    }
}
