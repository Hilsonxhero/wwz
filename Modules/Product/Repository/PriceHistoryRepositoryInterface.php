<?php

namespace Modules\Product\Repository;

interface PriceHistoryRepositoryInterface
{
    public function find($id);
    public function chart($variant, $date);
    public function all();
    public function create($data);
    public function update($id, $data);
    public function show($id);
    public function delete($id);
}
