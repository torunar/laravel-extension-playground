<?php

namespace App;

class User
{
    public array $usergroup_ids = [];

    public function __construct(array $usergroup_ids)
    {
        $this->usergroup_ids = $usergroup_ids;
    }
}