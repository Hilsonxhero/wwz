<?php

namespace Modules\Product\Repository;

interface ProductVariantRepositoryInterface
{
    public function find($id);
    public function all();
    public function create($data);
    public function update($id, $data);
    public function show($id);
    public function values($id);
    public function delete($id);
}
