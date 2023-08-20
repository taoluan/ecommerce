<?php

namespace App\Repositories;

use App\Models\Store;
use App\Repositories\Interface\StoreInterface;

class StoreRepository extends BaseRepository implements StoreInterface
{

    public function setModel()
    {
        return Store::class;
    }

    public function getListByUser($userId, $search)
    {
        $store = $this->_model->where("user_id", $userId);
        if (!empty($search["name"])) {
            $store->where("name", "LIKE", "%{$search["name"]}%");
        }
        if (!empty($search["address"])) {
            $store->where("address", "LIKE", "%{$search["address"]}%");
        }

        if (!empty($search["active"])) {
            $store->where("active", $search["active"]);
        }

        return $store->paginate();
    }
}
