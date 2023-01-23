<?php

namespace Modules\Payment\Repository;

interface PaymentRepositoryInterface
{
    public function find($id);
    public function findByReferenceCode($reference_code);
    public function all();
    public function allActive();
    public function create($data);
    public function update($id, $data);
    public function show($id);
    public function delete($id);
}
