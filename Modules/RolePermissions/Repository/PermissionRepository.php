<?php

namespace Modules\RolePermissions\Repository;

use App\Services\ApiService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Spatie\Permission\Models\Permission;


class PermissionRepository implements PermissionRepositoryInterface
{

    public function all()
    {
        return Permission::orderBy('created_at', 'desc')
            ->get();
    }

    public function paginate()
    {
        return Permission::orderBy('created_at', 'desc')
            ->paginate();
    }

    public function allActive()
    {
        return Permission::orderBy('created_at', 'desc')
            ->paginate();
    }


    public function create($data)
    {
        $permission =  Permission::query()->create($data);
        return $permission;
    }
    public function update($id, $data)
    {
        $permission = $this->find($id);
        $permission->update($data);
        return $permission;
    }
    public function show($id)
    {
        $permission = $this->find($id);
        return $permission;
    }

    public function find($id)
    {
        try {
            $permission = Permission::query()->where('id', $id)->firstOrFail();
            return $permission;
        } catch (ModelNotFoundException $e) {
            return  ApiService::_response(trans('response.responses.404'), 404);
        }
    }
    public function delete($id)
    {
        $permission = $this->find($id);
        $permission->delete();
    }
}
