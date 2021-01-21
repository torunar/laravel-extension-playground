<?php

namespace App\Products\GraphQL\Queries;

use App\Products\Commands\GetAllProductsCommand;
use App\Products\GraphQL\Types\ProductType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

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

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, SelectFields $fields)
    {
        $relations = $fields->getRelations();

        $cmd = new GetAllProductsCommand();

        return $cmd->run($relations);
    }
}
