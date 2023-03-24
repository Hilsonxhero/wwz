<?php

namespace Modules\Product\Repository;

interface ProductWishRepositoryInterface
{
    public function get($user);
    public function delete($id);
}
