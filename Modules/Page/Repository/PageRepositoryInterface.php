<?php

namespace Modules\Page\Repository;

interface PageRepositoryInterface
{
    public function find($id);
    public function findByTitle($title);
    public function all();
    public function create($data);
    public function update($id, $data);
    public function show($id);
    public function delete($id);
}
