<?php

namespace Modules\Product\Repository;

interface VariantGroupRepositoryInterface
{
    public function find($id);
    public function select($id);
    public function all();
    public function active();
    public function create($data);
    public function update($id, $data);
    public function show($id);
    public function values($id);
    public function delete($id);
}
