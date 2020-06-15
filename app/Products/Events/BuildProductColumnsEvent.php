<?php

namespace App\Products\Events;

use App\Products\QueryBuilder;
use Illuminate\Database\Query\Builder;

class BuildProductColumnsEvent
{
    /**
     * @var \App\Products\QueryBuilder
     */
    public QueryBuilder $query_builder;

    /**
     * @var \Illuminate\Database\Query\Builder
     */
    public Builder $query;

    public function __construct(QueryBuilder $query_builder, Builder $query)
    {
        $this->query_builder = $query_builder;
        $this->query = $query;
    }
}