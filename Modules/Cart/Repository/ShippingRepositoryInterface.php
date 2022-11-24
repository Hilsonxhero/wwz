<?php

namespace Modules\Cart\Repository;

interface ShippingRepositoryInterface
{
    public function find($id);
    public function all();
    public function create($data);
    public function update($id, $data);
    public function show($id);
    public function delete($id);
}