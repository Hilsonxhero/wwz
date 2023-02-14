<?php

namespace Modules\Product\Repository;

interface ProductReviewRepositoryInterface
{
    public function find($id);
    public function get();
    public function all();
    public function create($data);
    public function update($id, $data);
    public function show($id);
    public function delete($id);
}
