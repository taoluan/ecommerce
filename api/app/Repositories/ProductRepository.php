<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Interface\ProductInterface;

class ProductRepository extends BaseRepository implements ProductInterface
{

    public function setModel()
    {
        return Product::class;
    }

    public function getListByStore($storeId, $search) {
        $store = $this->_model->where("store_id", $storeId)->with(["category", "inventory"]);
        if (!empty($search["name"])) {
            $store->where("name", "LIKE", "%{$search["name"]}%");
        }
        if (!empty($search["active"])) {
            $store->where("active", $search["active"]);
        }
        return $store->paginate();
    }
}
