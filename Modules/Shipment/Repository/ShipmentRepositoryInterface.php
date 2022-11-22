<?php

namespace Modules\Shipment\Repository;

interface ShipmentRepositoryInterface
{
    public function find($id);
    public function default($delivery);
    public function all();
    public function create($data);
    public function update($id, $data);
    public function show($id);
    public function delete($id);
}
