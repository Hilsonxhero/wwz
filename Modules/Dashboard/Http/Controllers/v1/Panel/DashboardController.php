<?php

namespace Modules\Dashboard\Http\Controllers\v1\Panel;


use App\Services\ApiService;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Category\Entities\Category;
use Modules\Dashboard\Repository\DashboardRepositoryInterface;
use Modules\Order\Entities\Order;

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
        // return Cache::remember('stats.dashboard', 60, function () {
        // });


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
