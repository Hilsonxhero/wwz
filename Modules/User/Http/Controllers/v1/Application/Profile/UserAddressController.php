<?php

namespace Modules\User\Http\Controllers\v1\Application\Profile;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\User\Http\Requests\App\UserAddressRequest;
use Modules\User\Repository\UserAddressRepositoryInterface;
use Modules\User\Transformers\App\UserAddressResource;

class UserAddressController extends Controller
{
    private $addressRepo;

    public function __construct(UserAddressRepositoryInterface $addressRepo)
    {

        $this->addressRepo = $addressRepo;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $addresses = $this->addressRepo->all();
        return UserAddressResource::collection($addresses);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(UserAddressRequest $request)
    {
        $address = $this->addressRepo->create($request);
        ApiService::_success(trans('response.responses.200'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}