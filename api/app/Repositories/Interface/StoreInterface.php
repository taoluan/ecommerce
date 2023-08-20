<?php

namespace App\Repositories\Interface;

interface StoreInterface
{
    public function getListByUser($userId, $search);
}
