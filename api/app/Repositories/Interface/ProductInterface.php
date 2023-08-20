<?php

namespace App\Repositories\Interface;

interface ProductInterface
{
    public function getListByStore($storeId, $search);
}
