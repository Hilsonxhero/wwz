<?php

namespace Modules\Product\Repository;

interface IncredibleProductRepositoryInterface
{
    public function find($id);
    public function select($id);
    public function all();
    public function take();
    public function create($data);
    public function update($id, $data);
    public function show($id);
    public function values($id);
    public function delete($id);
}
