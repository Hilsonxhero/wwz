<?php

namespace Modules\Web\Http\Controllers\v1\App;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Product\Repository\RecommendationRepo;
use Modules\Product\Repository\RecommendationRepoInterface;
use Modules\Product\Transformers\App\RecommendationResource;

class RecommendationController extends Controller
{
    private $recommendationRepo;

    public function __construct(RecommendationRepoInterface $recommendationRepo)
    {
        $this->recommendationRepo = $recommendationRepo;
    }

    /**
     * Display recommendation categories .
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $recommendations = $this->recommendationRepo->get();

        return RecommendationResource::collection($recommendations);
    }
}
