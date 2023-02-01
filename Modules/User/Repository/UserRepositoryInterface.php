<?php

namespace Modules\User\Repository;

interface UserRepositoryInterface
{
    public function find($id);
    public function findByPhone($phone);
    public function all();
    public function select($query);
    public function allActive();
    public function create($data);
    public function update($id, $data);
    public function show($id);
    public function cities($id);
    public function delete($id);
}
