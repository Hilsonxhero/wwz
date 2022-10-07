<?php

namespace Modules\Shipment\Repository;

interface ShipmentDateRepositoryInterface
{
    public function find($id);
    public function get($shipment);
    public function all();
    public function create($data);
    public function update($id, $data);
    public function show($id);
    public function delete($id);
}
