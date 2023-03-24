<?php

namespace Modules\User\Http\Controllers\v1\Application\Profile;

use App\Services\ApiService;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\User\Repository\UserRepository;
use Modules\Product\Repository\ProductWishRepositoryInterface;
use Modules\Product\Transformers\App\WishResource;

class UserWishController extends Controller
{
    private $userRepo;
    private $wishRepo;

    public function __construct(UserRepository $userRepo, ProductWishRepositoryInterface $wishRepo)
    {
        $this->userRepo = $userRepo;
        $this->wishRepo = $wishRepo;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $user = auth()->user();

        $wishes = $this->wishRepo->get($user->id);

        $wishes_collection = WishResource::collection($wishes);

        ApiService::_success(array(
            'wishes' => $wishes_collection->items(),
            'pager' => array(
                'pages' => $wishes_collection->lastPage(),
                'total' => $wishes_collection->total(),
                'current_page' => $wishes_collection->currentPage(),
                'per_page' => $wishes_collection->perPage(),
            )
        ));
    }

    public function destroy($id)
    {
        $wishe = $this->wishRepo->delete($id);
        ApiService::_success(trans('response.responses.200'));
    }
}
