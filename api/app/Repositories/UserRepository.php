<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interface\UserInterface;

class UserRepository extends BaseRepository implements UserInterface
{

    public function setModel()
    {
        return User::class;
    }
}
