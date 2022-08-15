<?php

namespace Modules\RolePermissions\Http\Controllers\v1\Panel;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\RolePermissions\Repository\PermissionRepositoryInterface;
use Modules\RolePermissions\Transformers\PermissionResource;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    private $permissionRepo;

    public function __construct(PermissionRepositoryInterface $permissionRepo)
    {
        $this->permissionRepo = $permissionRepo;
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        if ($request->has('paginate')) $permissions = $this->permissionRepo->paginate();
        else $permissions = $this->permissionRepo->all();
        return PermissionResource::collection($permissions);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        ApiService::Validator($request->all(), [
            'name' => ['required']
        ]);

        $data = [
            'name' => $request->name
        ];

        $permission = $this->permissionRepo->create($data);

        ApiService::_success(trans('response.responses.200'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $permission = $this->permissionRepo->show($id);
        ApiService::_success($permission);
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
            'name' => ['required']
        ]);

        $data = [
            'name' => $request->name
        ];

        $permission = $this->permissionRepo->update($id, $data);

        ApiService::_success(trans('response.responses.200'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $permission = $this->permissionRepo->delete($id);
        ApiService::_success(trans('response.responses.200'));
    }
}
