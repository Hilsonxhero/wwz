<?php

namespace Modules\Product\Repository;

interface RecommendationProductRepoInterface
{
    public function find($id);
    public function select($id);
    public function all();
    public function create($data);
    public function update($id, $data);
    public function show($id);
    public function delete($id);
}
