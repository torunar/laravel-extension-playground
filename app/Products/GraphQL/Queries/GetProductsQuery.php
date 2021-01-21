<?php

namespace App\Products\GraphQL\Queries;

use App\Products\Commands\GetAllProductsCommand;
use App\Products\GraphQL\Types\ProductType;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

class GetProductsQuery extends Query
{
    const ID = 'GetProducts';

    protected $attributes = [
        'name' => self::ID,
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type(ProductType::ID));
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectedFields)
    {
        /** @var \Rebing\GraphQL\Support\SelectFields $fields */
        $fields = $getSelectedFields();
        $relations = $fields->getRelations();

        $cmd = new GetAllProductsCommand();

        return $cmd->run($relations);
    }
}
