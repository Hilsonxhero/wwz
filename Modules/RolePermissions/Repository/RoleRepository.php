<?php

namespace Modules\RolePermissions\Repository;

use App\Services\ApiService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Spatie\Permission\Models\Role;


class RoleRepository implements RoleRepositoryInterface
{

    public function all()
    {
        return Role::orderBy('created_at', 'desc')
            ->paginate();
    }

    public function allActive()
    {
        return Role::orderBy('created_at', 'desc')
            ->paginate();
    }


    public function create($data)
    {
        $role =  Role::query()->create($data)->syncPermissions($data['permissions']);
        return $role;
    }
    public function update($id, $data)
    {
        $role = $this->find($id);
        $role->syncPermissions($data['permissions'])->update(['name' => $data['name']]);
        return $role;
    }
    public function show($id)
    {
        $role = $this->find($id);
        return $role;
    }

    public function find($id)
    {
        try {
            $role = Role::query()->where('id', $id)->with('permissions')->firstOrFail();
            return $role;
        } catch (ModelNotFoundException $e) {
            return  ApiService::_response(trans('response.responses.404'), 404);
        }
    }
    public function delete($id)
    {
        $role = $this->find($id);
        $role->delete();
    }
}
