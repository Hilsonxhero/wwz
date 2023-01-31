<?php

namespace Modules\Voucher\Repository;

interface VoucherRepositoryInterface
{
    public function find($id);
    public function all();
    public function take();
    public function create($data);
    public function update($id, $data);
    public function show($id);
    public function delete($id);
}
