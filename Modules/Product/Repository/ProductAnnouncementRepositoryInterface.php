<?php

namespace Modules\Product\Repository;

interface ProductAnnouncementRepositoryInterface
{
    public function get($user);
    public function delete($id);
    public function create($data);
}
