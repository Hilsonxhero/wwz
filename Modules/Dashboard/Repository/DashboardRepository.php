<?php

namespace Modules\Dashboard\Repository;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Services\ApiService;
use Morilog\Jalali\Jalalian;
use Modules\Order\Entities\Order;
use Modules\Order\Entities\OrderShippingItem;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Order\Enums\OrderStatus;

class DashboardRepository implements DashboardRepositoryInterface
{

    public function totalSales()
    {
        return Order::select('payable_price')->where('status', OrderStatus::Sent->value)->get()->sum('payable_price');
    }

    public function monthlySales()
    {
        $now_jalali = \Morilog\Jalali\Jalalian::now();

        $sub_months_jalali = $now_jalali->subMonths(1);

        $start_date = \Morilog\Jalali\Jalalian::fromFormat('Y-m-d', $sub_months_jalali->format('Y-m-d'))->toCarbon();

        return Order::query()->where('status', OrderStatus::Sent->value)->whereBetween('created_at', [$start_date, now()])
            ->get()->sum('payable_price');
    }

    public function yearlySales()
    {
        $now_jalali = \Morilog\Jalali\Jalalian::now();

        $sub_months_jalali = $now_jalali->subYears(1);

        $start_date = \Morilog\Jalali\Jalalian::fromFormat('Y-m-d', $sub_months_jalali->format('Y-m-d'))->toCarbon();

        return Order::query()->where('status', OrderStatus::Sent->value)->whereBetween('created_at', [$start_date, now()])
            ->get()->sum('payable_price');
    }

    public function dailySales()
    {
        $now_jalali = \Morilog\Jalali\Jalalian::now();

        $sub_months_jalali = $now_jalali->subDays(1);

        $start_date = \Morilog\Jalali\Jalalian::fromFormat('Y-m-d', $sub_months_jalali->format('Y-m-d'))->toCarbon();

        return Order::query()->where('status', OrderStatus::Sent->value)->whereBetween('created_at', [$start_date, now()])
            ->get()->sum('payable_price');
    }
    public function severalMonthsCategories()
    {
        $now_jalali = \Morilog\Jalali\Jalalian::now();

        $sub_months_jalali = $now_jalali->subMonths(5);

        $start_date = \Morilog\Jalali\Jalalian::fromFormat('Y-m-d', $sub_months_jalali->format('Y-m-d'))->toCarbon();

        $results = OrderShippingItem::with('product.category')
            ->whereBetween('created_at', [$start_date, now()->addMonth(1)])
            ->get()
            ->map(function ($orderItem) {
                return [
                    'category' => $orderItem->product->category->title,
                    'quantity' => 1,
                ];
            })
            ->groupBy('category')
            ->map(function ($orders) {
                return $orders->sum('quantity');
            });

        $categories = $results->keys()->toArray();
        $values = $results->values()->toArray();

        return array(
            'labels' => $categories,
            'values' => $values,
            'total' => array_sum($values),
            'start_at' => $sub_months_jalali->format('%B %d، %Y'),
            'end_at' => $now_jalali->format('%B %d، %Y')
        );
    }

    public function severalMonthsSales()
    {

        $now_jalali = \Morilog\Jalali\Jalalian::now();

        $sub_months_jalali = $now_jalali->subMonths(5);

        $start_date = \Morilog\Jalali\Jalalian::fromFormat('Y-m-d', $sub_months_jalali->format('Y-m-d'))->toCarbon();

        $data = collect(CarbonPeriod::create($start_date, '1 month', now()->addMonth(1)))
            ->map(function ($period_item) {
                $jDate = Jalalian::fromDateTime($period_item);
                [$first_month, $end_month] = $this->getFirstAndLastDayOfMonth($jDate->getYear(), $jDate->getMonth());
                $orders = Order::query()
                    ->whereDate('created_at', '>=', $first_month)
                    ->whereDate('created_at', '<=', $end_month)
                    ->get();

                $totalSales = $orders->reduce(function ($total, $orderItem) {
                    return $total + $orderItem->payable_price;
                }, 0);

                return [
                    'label' => $jDate->format('%B'),
                    'value' => $totalSales,
                ];
            })
            ->toArray();

        $labels = array_column($data, 'label');
        $values = array_column($data, 'value');

        return array(
            'labels' => $labels, 'values' => $values,
            'total' => array_sum($values),
            'start_at' => $sub_months_jalali->format('%B %d، %Y'),
            'end_at' => $now_jalali->format('%B %d، %Y')
        );
    }

    public function severalMonthsOrders()
    {

        $now_jalali = \Morilog\Jalali\Jalalian::now();

        $sub_months_jalali = $now_jalali->subMonths(5);

        $start_date = \Morilog\Jalali\Jalalian::fromFormat('Y-m-d', $sub_months_jalali->format('Y-m-d'))->toCarbon();

        $data = collect(CarbonPeriod::create($start_date, '1 month', now()->addMonth(1)))
            ->map(function ($period_item) {
                $jDate = Jalalian::fromDateTime($period_item);
                [$first_month, $end_month] = $this->getFirstAndLastDayOfMonth($jDate->getYear(), $jDate->getMonth());
                $count = Order::query()
                    ->whereDate('created_at', '>=', $first_month)
                    ->whereDate('created_at', '<=', $end_month)
                    ->count();
                return [
                    'label' => $jDate->format('%B'),
                    'value' => $count,

                ];
            })
            ->toArray();

        $labels = array_column($data, 'label');
        $values = array_column($data, 'value');

        return array(
            'labels' => $labels, 'values' => $values,
            'total' => array_sum($values),
            'start_at' => $sub_months_jalali->format('%B %d، %Y'),
            'end_at' => $now_jalali->format('%B %d، %Y')
        );
    }

    public function getFirstAndLastDayOfMonth($year, $month)
    {
        $date_count = (new Jalalian($year, $month, 1))->getMonthDays();
        $first_month = (new Jalalian($year, $month, 1))->toCarbon();
        $end_month = (new Jalalian($year, $month, $date_count))->toCarbon();
        return [$first_month, $end_month];
    }
}
