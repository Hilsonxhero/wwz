<?php

namespace Modules\Product\Http\Controllers\v1\Panel;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Routing\Controller;
use Modules\Product\Entities\VariantGroup;
use Modules\Product\Repository\ProductRepositoryInterface;
use Modules\Product\Transformers\VariantResource;
use Modules\Product\Transformers\VariantGroupResource;
use Modules\Product\Repository\VariantGroupRepositoryInterface;

class VariantGroupController extends Controller
{
    private $groupRepo;
    private $productRepo;
    public function __construct(VariantGroupRepositoryInterface $groupRepo, ProductRepositoryInterface $productRepo)
    {
        $this->groupRepo = $groupRepo;
        $this->productRepo = $productRepo;
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $groups = $this->groupRepo->all();
        return VariantGroupResource::collection($groups);
        // ApiService::_success($groups);
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function list($id)
    {
        $product = $this->productRepo->find($id);

        $combinations = $product->combinations;

        $groupd_combinations = $combinations->mapToGroups(function ($ss) {
            return [$ss['variant']['product_variant_id'] => $ss['variant']['id']];
        })->values()->toArray();


        if (count($combinations) >= 1) {
            $groups = $combinations->map(function ($orders) {
                return  $orders->variant->group;
            })->unique('id');
        } else {
            $groups = $this->groupRepo->active();
        }


        $group_collections =  VariantGroupResource::collection($groups);

        ApiService::_success(array(
            'groups' => $group_collections,
            'has_variants' => !!count($combinations),
            'combinations' => $groupd_combinations
        ));
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
            'type' => ['required', Rule::in(VariantGroup::$types)],
            'order' => ['nullable', 'boolean']
        ]);
        $data = [
            'name' => $request->name,
            'type' => $request->type,
        ];

        $this->groupRepo->create($data);

        ApiService::_success(trans('response.responses.200'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $group = $this->groupRepo->find($id);
        return new VariantGroupResource($group);
        // ApiService::_success($group);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function values($id)
    {
        $values = $this->groupRepo->values($id);
        return VariantResource::collection($values);
        // ApiService::_success($values);
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
            'type' => ['required', Rule::in(VariantGroup::$types)],
            'order' => ['nullable', 'boolean']
        ]);
        $data = [
            'name' => $request->name,
            'type' => $request->type,
        ];

        $this->groupRepo->update($id, $data);

        ApiService::_success(trans('response.responses.200'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->groupRepo->delete($id);
        ApiService::_success(trans('response.responses.200'));
    }
}
