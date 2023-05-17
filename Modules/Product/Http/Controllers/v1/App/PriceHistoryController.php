<?php

namespace Modules\Product\Http\Controllers\v1\App;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Services\ApiService;
use Illuminate\Http\Request;
use Morilog\Jalali\Jalalian;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Product\Entities\Product;
use PhpParser\Node\Expr\Cast\Object_;
use Modules\Product\Entities\PriceHistory;
use Modules\Product\Repository\PriceHistoryRepositoryInterface;
use Modules\Product\Repository\ProductRepositoryInterface;
use Modules\Product\Transformers\ProductVariantResource;
use Modules\Product\Transformers\App\PriceHistoryResource;

class PriceHistoryController extends Controller
{


    public $productRepo;

    public $priceRepo;

    public function __construct(ProductRepositoryInterface $productRepo, PriceHistoryRepositoryInterface $priceRepo)
    {
        $this->productRepo = $productRepo;
        $this->priceRepo = $priceRepo;
    }

    /**
     * Display product price history
     * @param Request $request
     * @return Response
     */
    public function show(Request $request, $id)
    {
        $product = $this->productRepo->show($id);

        $now_jalali = \Morilog\Jalali\Jalalian::now();

        $sub_months_jalali = $now_jalali->subMonths(1);

        $start_date = \Morilog\Jalali\Jalalian::fromFormat('Y-m-d', $sub_months_jalali->format('Y-m-d'))->toCarbon();

        $histories = array();

        $labels = array();

        $variants = ProductVariantResource::collection($product->variants);

        $data = collect(CarbonPeriod::create($start_date, '1 day', now()));

        foreach ($data as $key => $period_item) {
            $jDate = Jalalian::fromDateTime($period_item);
            array_push($labels, $jDate->format('%B ,%d، %Y'));
        }

        foreach ($variants as $key => $variant) {

            $chart_data = array();

            foreach ($data as $key => $period_item) {

                $jDate = Jalalian::fromDateTime($period_item);

                $history_value = [
                    'seller_id' => $variant->seller_id,
                    'product_id' => $variant->product_id,
                    'product_variant_id' => $variant->id,
                    'price' => $variant->price,
                    'discount_price' => $variant->calculate_total_discount_price,
                    'date' =>  formatGregorian($variant->created_at),
                ];

                $history = $this->priceRepo->chart($variant, $period_item);

                if ($history) {
                    $history = new PriceHistoryResource($history);
                    $history_value = $history;
                    array_push($chart_data, $history);
                } else {
                    array_push($chart_data, [...$history_value, 'date' =>  $jDate->format('%B ,%d، %Y')]);
                }
            }

            array_push($histories, ['history' =>  $chart_data, 'combinations' => $variant->combinations]);
        }

        return ApiService::_success(array(
            'labels' => $labels,
            'values' => $histories,
            'start_at' => $sub_months_jalali->format('%B %d، %Y'),
            'end_at' => $now_jalali->format('%B %d، %Y')
        ));
    }

    public function getFirstAndLastDayOfMonth($year, $month)
    {
        $date_count = (new Jalalian($year, $month, 1))->getMonthDays();
        $first_month = (new Jalalian($year, $month, 1))->toCarbon();
        $end_month = (new Jalalian($year, $month, $date_count))->toCarbon();
        return [$first_month, $end_month];
    }
}
