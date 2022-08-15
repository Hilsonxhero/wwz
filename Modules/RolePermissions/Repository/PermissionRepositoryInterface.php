<?php

namespace Modules\RolePermissions\Repository;

interface PermissionRepositoryInterface
{
    public function find($id);
    public function all();
    public function paginate();
    public function allActive();
    public function create($data);
    public function update($id, $data);
    public function show($id);
    public function delete($id);
}
