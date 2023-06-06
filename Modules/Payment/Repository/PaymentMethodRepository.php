<?php

namespace Modules\Payment\Repository;

use App\Services\ApiService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Category\Entities\Category;
use Modules\Payment\Entities\PaymentMethod;
use Modules\Payment\Enums\PaymentMethodStatus;

class PaymentMethodRepository implements PaymentMethodRepositoryInterface
{

    public function all()
    {
        return PaymentMethod::orderBy('created_at', 'desc')
            ->paginate();
    }

    public function allActive()
    {
        return PaymentMethod::orderBy('created_at', 'desc')
            ->where('status', PaymentMethodStatus::ENABLE->value)
            ->paginate();
    }

    public function create($data)
    {
        $method =  PaymentMethod::query()->create($data);
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
            $method = PaymentMethod::query()->where('id', $id)->firstOrFail();
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
