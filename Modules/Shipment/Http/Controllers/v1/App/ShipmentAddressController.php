<?php

namespace Modules\Shipment\Http\Controllers\v1\App;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\User\Repository\UserAddressRepositoryInterface;

class ShipmentAddressController extends Controller
{
    private $addressRepo;
    public function __construct(UserAddressRepositoryInterface $addressRepo)
    {

        $this->addressRepo = $addressRepo;
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function change(Request $request)
    {
        ApiService::Validator($request->all(), [
            'address_id' => ['required', 'exists:addresses,id']
        ]);

        $user = auth()->user();

        $default_address = $user->default_address;

        if ($default_address) {
            $this->addressRepo->update($default_address->id, ['is_default' => false]);
        }

        $address = $this->addressRepo->update($request->input('address_id'), ['is_default' => true]);

        ApiService::_success($address);
    }
}
