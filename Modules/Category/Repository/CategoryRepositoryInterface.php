<?php

namespace Modules\Category\Repository;

interface CategoryRepositoryInterface
{
    public function find($id);
    public function select($query);
    public function search($query);
    public function all();
    public function group();
    public function mainCategories();
    public function allActive();
    public function create($data);
    public function update($id, $data);
    public function show($id);
    public function delete($id);
}
