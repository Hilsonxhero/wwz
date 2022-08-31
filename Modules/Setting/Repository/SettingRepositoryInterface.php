<?php

namespace Modules\Setting\Repository;

interface SettingRepositoryInterface
{
    public function find($id);
    public function all();
    public function create($data);
    public function update($id, $data);
    public function show($id);
    public function delete($id);
}
