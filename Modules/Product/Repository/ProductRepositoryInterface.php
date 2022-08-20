<?php

namespace Modules\Product\Repository;

interface ProductRepositoryInterface
{
    public function find($id);
    public function all();
    public function variants($id);
    public function createVariants($id, $variants);
    public function updateVariants($id, $variants);
    public function allActive();
    public function create($data);
    public function update($id, $data);
    public function show($id);
    public function values($id);
    public function delete($id);
}