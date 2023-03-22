<?php

namespace Modules\Dashboard\Http\Controllers\v1\Panel;


use App\Services\ApiService;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
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
        // return Cache::remember('stats.dashboard', 60, function () {
        // });

        $several_months_sales =  $this->dashboardRepo->severalMonthsSales();
        $several_months_categories = $this->dashboardRepo->severalMonthsCategories();


        ApiService::_success(array(
            'several_months_sales' => $several_months_sales,
            'several_months_categories' => $several_months_categories,
        ));
    }
}
