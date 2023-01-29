<?php

namespace Modules\Product\Repository;

interface RecommendationRepoInterface
{
    public function find($id);
    public function select($id);
    public function get();
    public function all();
    public function create($data);
    public function update($id, $data);
    public function show($id);
    public function delete($id);
}
