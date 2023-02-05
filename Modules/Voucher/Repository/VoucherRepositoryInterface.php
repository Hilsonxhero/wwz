<?php

namespace Modules\Voucher\Repository;

interface VoucherRepositoryInterface
{
    public function find($id);
    public function check($data);
    public function all();
    public function take();
    public function create($data);
    public function createMany($data);
    public function update($id, $data);
    public function show($id);
    public function voucherables($id);
    public function delete($id);
}
