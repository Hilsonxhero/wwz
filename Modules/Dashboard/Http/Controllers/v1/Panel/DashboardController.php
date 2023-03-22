<?php

namespace Modules\Dashboard\Http\Controllers\v1\Panel;


use App\Services\ApiService;
use Illuminate\Http\Response;
use Modules\Order\Entities\Order;
use Illuminate\Routing\Controller;
use Modules\Order\Enums\OrderStatus;
use Modules\Category\Entities\Category;
use Modules\Order\Entities\OrderShipping;
use Modules\Product\Entities\ProductVariant;
use Modules\Order\Entities\OrderShippingItem;
use Modules\Order\Repository\OrderRepositoryInterface;
use Modules\Dashboard\Repository\DashboardRepositoryInterface;

class DashboardController extends Controller
{
    private $dashboardRepo;
    public function __construct(DashboardRepositoryInterface $dashboardRepo)
    {
        $this->dashboardRepo = $dashboardRepo;
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $total_sales =  $this->dashboardRepo->totalSales();
        $monthly_sales =  $this->dashboardRepo->monthlySales();
        $yearly_sales =  $this->dashboardRepo->yearlySales();
        $daily_sales =  $this->dashboardRepo->dailySales();
        $several_months_sales = $this->dashboardRepo->severalMonthsSales();
        $several_months_orders =  $this->dashboardRepo->severalMonthsOrders();
        $several_months_categories = $this->dashboardRepo->severalMonthsCategories();


        ApiService::_success(array(
            'total_sales' => $total_sales,
            'monthly_sales' => $monthly_sales,
            'yearly_sales' => $yearly_sales,
            'daily_sales' => $daily_sales,
            'several_months_sales' => $several_months_sales,
            'several_months_orders' => $several_months_orders,
            'several_months_categories' => $several_months_categories,
        ));
    }
}
