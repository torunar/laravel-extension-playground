<?php

namespace App\Products\Commands;

use App\Products\Events\GetVisibleProductsEvent;
use App\Products\Product;
use App\User;
use Illuminate\Support\Collection;

class GetVisibleProductsCommand
{
    /**
     * @var \App\User
     */
    protected User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function run(): Collection
    {
        /** @var \App\Products\QueryBuilder $query_builder */
        $query_builder = Product::query()->whereActive()->whereInStock();

        event(new GetVisibleProductsEvent($query_builder, $this->user));

        return $query_builder->get();
    }
}