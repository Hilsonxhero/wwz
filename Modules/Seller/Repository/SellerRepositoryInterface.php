<?php

namespace Modules\Seller\Repository;

interface SellerRepositoryInterface
{
    public function find($id);
    public function all();
    public function create($data);
    public function update($id, $data);
    public function show($id);
    public function default();
    public function delete($id);
}
