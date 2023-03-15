<?php

namespace Modules\Order\Repository;

interface OrderRepositoryInterface
{
    public function find($id);
    public function tabs($user);
    public function all();
    public function allActive();
    public function create($data);
    public function update($id, $data);
    public function show($id);
    public function delete($id);
}
