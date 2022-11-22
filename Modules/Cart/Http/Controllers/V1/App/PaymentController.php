<?php

namespace Modules\Cart\Http\Controllers\v1\App;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Modules\Cart\Facades\Cart;
use Illuminate\Routing\Controller;
use Modules\User\Transformers\UserResource;
use Modules\Cart\Transformers\App\CartResource;
use Modules\Cart\Transformers\App\PaymentResource;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function init()
    {
        $user  = auth()->user();

        $data = array(
            'cart' => new CartResource(Cart::content()),
            'user' => new UserResource($user),
        );

        ApiService::_success($data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }
}
