<?php

namespace App\Products\Events;

use App\Products\QueryBuilder;
use App\User;

class GetVisibleProductsEvent
{
    public QueryBuilder $query_builder;

    public User $user;

    public function __construct(QueryBuilder $query_builder, User $user)
    {
        $this->query_builder = $query_builder;
        $this->user = $user;
    }
}