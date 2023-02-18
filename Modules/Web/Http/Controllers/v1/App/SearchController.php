<?php

namespace Modules\Web\Http\Controllers\v1\App;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Product\Entities\Product;
use Modules\Product\Repository\RecommendationRepo;
use Hilsonxhero\ElasticVision\Domain\Syntax\Matching;
use Hilsonxhero\ElasticVision\Domain\Syntax\MatchPhrase;
use Modules\Category\Entities\Category;
use Modules\Product\Transformers\App\RecommendationResource;

class SearchController extends Controller
{
    private $recommendationRepo;

    public function __construct(RecommendationRepo $recommendationRepo)
    {
        $this->recommendationRepo = $recommendationRepo;
    }

    /**
     * Display recommendation categories .
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {

        $categories = Category::search()->must(new MatchPhrase('title', $request->q))->get();

        // $categories = Category::search($request->q)
        //     ->field('title')
        //     ->field('title_en')
        //     ->get();

        // $search = Product::search()->must(new Matching('title_fa', $request->q))->get();
        ApiService::_success($categories);
    }
}
