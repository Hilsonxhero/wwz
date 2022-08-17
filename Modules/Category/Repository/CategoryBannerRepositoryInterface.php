<?php

namespace Modules\Category\Repository;

interface CategoryBannerRepositoryInterface
{
    public function find($id);
    public function all();
    public function allActive();
    public function create($category,$data);
    public function update($id, $data);
    public function show($id);
    public function delete($id);
}
