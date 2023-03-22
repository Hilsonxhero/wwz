<?php

namespace Modules\Order\Repository;

use Faker\Core\Number;
use App\Services\ApiService;
use Modules\Order\Entities\Order;

use Illuminate\Support\Facades\DB;
use Modules\Order\Enums\OrderStatus;
use Modules\Product\Entities\ProductVariant;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class OrderRepository implements OrderRepositoryInterface
{

    public function all()
    {
        return Order::orderBy('created_at', 'desc')
            ->paginate();
    }

    public function tabs($user)
    {
        $orders = DB::table('orders')
            ->where('user_id', $user->id)
            ->selectRaw('COUNT(*) as orders_count')
            ->selectRaw('SUM(status = "processed" OR status = "wait_payment" OR status = "leaving_center" OR status = "received_center" OR status = "delivery_dispatcher" OR status = "delivery_customer") as progress_count')
            ->selectRaw('SUM(status = "sent") as sent_count')
            ->selectRaw('SUM(status = "returned") as returned_count')
            ->selectRaw('SUM(status = "canceled_system") as canceled_count')
            ->first();

        return array(
            array('status' => 'progress', 'title' => "جاری", 'count' => $orders->progress_count),
            array('status' => 'canceled', 'title' => "لغو شده", 'count' => $orders->canceled_count),
            array('status' => 'sent', 'title' => "تحویل شده", 'count' =>  $orders->sent_count),
            array('status' => 'returned', 'title' => "مرجوع شده", 'count' => $orders->returned_count)
        );
    }

    public function allActive()
    {
        return Order::orderBy('created_at', 'desc')

            ->paginate();
    }

    public function cancelUnpaidOrders()
    {
        $orders = Order::where([
            ['status', '=', OrderStatus::WaitPayment],
            ['payment_remaining_time', '<', now()]
        ])->get();

        $orderShippingItems = $orders->flatMap(fn ($order) => $order->order_shipping_items);

        $shippingItems = $orderShippingItems->map(fn ($item) => [
            'id' => $item->variant_id,
            'stock' => $item->variant->stock + $item->quantity
        ])->toArray();

        ProductVariant::query()->upsert($shippingItems, ['id'], ['stock']);

        return  Order::where([
            ['payment_remaining_time', '<', now()],
            ['status', '=', OrderStatus::WaitPayment]
        ])->update(['status' => OrderStatus::CanceledSystem->value]);
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
