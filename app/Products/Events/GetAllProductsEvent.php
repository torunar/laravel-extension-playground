<?php

namespace App\Products\Events;

use App\Products\QueryBuilder;

class GetAllProductsEvent
{
    /**
     * @var \App\Products\QueryBuilder
     */
    protected QueryBuilder $query_builder;

    public function __construct(QueryBuilder $query_builder)
    {
        $this->query_builder = $query_builder;
    }
}