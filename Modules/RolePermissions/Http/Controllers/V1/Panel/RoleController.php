<?php

namespace Modules\RolePermissions\Http\Controllers\v1\Panel;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\RolePermissions\Repository\RoleRepositoryInterface;
use Modules\RolePermissions\Transformers\RoleResource;

class RoleController extends Controller
{
    private $roleRepo;

    public function __construct(RoleRepositoryInterface $roleRepo)
    {
        $this->roleRepo = $roleRepo;
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $roles = $this->roleRepo->all();
        return RoleResource::collection($roles);
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
            'permissions' => ['required'],
        ]);

        $data = [
            'name' => $request->name,
            'permissions' => $request->permissions,
        ];

        $permission = $this->roleRepo->create($data);

        ApiService::_success(trans('response.responses.200'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $permission = $this->roleRepo->show($id);
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
            'name' => ['required'],
            'permissions' => ['required'],
        ]);

        $data = [
            'name' => $request->name,
            'permissions' => $request->permissions,
        ];

        $permission = $this->roleRepo->update($id, $data);

        ApiService::_success(trans('response.responses.200'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $permission = $this->roleRepo->delete($id);
        ApiService::_success(trans('response.responses.200'));
    }
}
