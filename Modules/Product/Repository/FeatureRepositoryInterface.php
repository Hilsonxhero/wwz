<?php

namespace Modules\Product\Repository;

interface FeatureRepositoryInterface
{
    public function find($id);
    public function select($id);
    public function all();
    public function allActive();
    public function create($data);
    public function update($id, $data);
    public function show($id);
    public function values($id);
    public function delete($id);
}
