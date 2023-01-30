<?php

namespace Modules\Category\Http\Controllers\v1\App;


use Illuminate\Http\File;
use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Category\Entities\Category;
use Modules\Category\Transformers\CategoryResource;
use Modules\Category\Transformers\CategoryCollection;
use Modules\Category\Repository\CategoryRepositoryInterface;
use Modules\Category\Transformers\App\CategoryResource as AppCategoryResource;

class CategoryController extends Controller
{

    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepo;

    public function __construct(CategoryRepositoryInterface $categoryRepo)
    {
        $this->categoryRepo = $categoryRepo;
    }


    public function index()
    {
        $categories = $this->categoryRepo->group();
        return  AppCategoryResource::collection($categories);
    }
}
