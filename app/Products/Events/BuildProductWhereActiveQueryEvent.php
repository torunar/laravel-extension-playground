<?php

namespace App\Products\Events;

use App\Products\QueryBuilder;

class BuildProductWhereActiveQueryEvent
{
    public QueryBuilder $query_builder;

    public array $conditions;

    public function __construct(QueryBuilder $query_builder, array $conditions)
    {
        $this->query_builder = $query_builder;
        $this->conditions = $conditions;
    }
}