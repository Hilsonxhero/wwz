<?php

namespace Modules\Order\Repository;

use App\Services\ApiService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use Modules\Order\Entities\Order;


class OrderRepository implements OrderRepositoryInterface
{

    public function all()
    {
        return Order::orderBy('created_at', 'desc')
            ->paginate();
    }

    public function allActive()
    {
        return Order::orderBy('created_at', 'desc')

            ->paginate();
    }

    public function create($data)
    {
        $payment =  Order::query()->create($data);
        return $payment;
    }

    public function update($id, $data)
    {

        $payment = $this->find($id);
        $payment->update($data);
        return $payment;
    }

    public function show($id)
    {
        $payment = $this->find($id);
        return $payment;
    }

    public function find($id)
    {

        try {
            $payment = Order::query()->where('id', $id)->firstOrFail();
            return $payment;
        } catch (ModelNotFoundException $e) {
            return  ApiService::_response(trans('response.responses.404'), 404);
        }
    }
    public function delete($id)
    {
        $payment = $this->find($id);
        if ($payment)   $payment->delete();
    }
}
