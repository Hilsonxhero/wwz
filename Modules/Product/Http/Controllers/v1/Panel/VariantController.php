<?php

namespace Modules\Product\Http\Controllers\v1\Panel;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Product\Repository\VariantRepositoryInterface;

class VariantController extends Controller
{
    private $variantRepo;
    public function __construct(VariantRepositoryInterface $variantRepo)
    {
        $this->variantRepo = $variantRepo;
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $groups = $this->variantRepo->all();
        ApiService::_success($groups);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        ApiService::Validator($request->all(), [
            'name' => ['required'],
            'variant_group_id' => ['required', 'exists:variant_groups,id'],
            'value' => ['required']
        ]);
        $data = [
            'name' => $request->name,
            'variant_group_id' => $request->variant_group_id,
            'value' => $request->value,
        ];

        $this->variantRepo->create($data);

        ApiService::_success(trans('response.responses.200'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $group = $this->variantRepo->find($id);
        ApiService::_success($group);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        ApiService::Validator($request->all(), [
            'name' => ['required'],
            'variant_group_id' => ['required', 'exists:variant_groups,id'],
            'value' => ['required']
        ]);
        $data = [
            'name' => $request->name,
            'type' => $request->type,
        ];

        $this->variantRepo->update($id, $data);

        ApiService::_success(trans('response.responses.200'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->variantRepo->delete($id);
        ApiService::_success(trans('response.responses.200'));
    }
}
