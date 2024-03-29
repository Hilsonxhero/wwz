<?php

namespace Modules\Product\Http\Controllers\v1\App;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Product\Http\Requests\App\ProductAnnouncementRequest;
use Modules\Product\Repository\ProductAnnouncementRepositoryInterface;
use Modules\Product\Repository\ProductVariantRepositoryInterface;

class ProductAnnouncementController extends Controller
{
    private $productVariantRepo;
    private $announcementRepo;


    public function __construct(
        ProductVariantRepositoryInterface $productVariantRepo,
        ProductAnnouncementRepositoryInterface $announcementRepo
    ) {
        $this->productVariantRepo = $productVariantRepo;
        $this->announcementRepo = $announcementRepo;
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(ProductAnnouncementRequest $request, $id)
    {
        $user = auth()->user();

        $this->announcementRepo->create([
            'product_variant_id' => $id,
            'user_id' => $user->id,
            'type' => $request->type,
            'via_sms' => true,
            'via_email' => true
        ]);

        ApiService::_success(trans('response.responses.200'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $user = auth()->user();

        $product_variant = $this->productVariantRepo->find($id);

        $product_variant->announcements()->detach(
            $user->id
        );

        ApiService::_success(trans('response.responses.200'));
    }
}
