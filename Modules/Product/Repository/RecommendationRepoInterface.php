<?php

namespace Modules\Product\Repository;

interface RecommendationRepoInterface
{
    public function find($id);
    public function select($query = null);
    public function get();
    public function all();
    public function create($data);
    public function update($id, $data);
    public function products($id);
    public function show($id);
    public function delete($id);
}
