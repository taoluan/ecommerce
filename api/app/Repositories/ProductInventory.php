<?php

namespace App\Repositories;

use App\Repositories\Interface\ProductInterface;
use App\Repositories\Interface\ProductInventoryInterface;

class ProductInventory extends BaseRepository implements ProductInventoryInterface
{

    public function setModel()
    {
        return ProductInventory::class;
    }

}
