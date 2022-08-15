<?php

namespace Modules\User\Repository;

interface UserRepositoryInterface
{
    public function find($id);
    public function all();
    public function allActive();
    public function create($data);
    public function update($id, $data);
    public function show($id);
    public function cities($id);
    public function delete($id);
}
