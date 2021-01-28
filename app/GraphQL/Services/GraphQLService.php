<?php

namespace App\GraphQL\Services;

use App\GraphQL\TypeResolver;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\GraphQL;

class GraphQLService
{
    protected GraphQL $graphQL;

    protected TypeResolver $typeResolver;

    public function __construct(GraphQL $graphQL, TypeResolver $typeResolver)
    {
        $this->graphQL = $graphQL;
        $this->typeResolver = $typeResolver;
    }

    public function type(string $type): Type
    {
        $type = $this->typeResolver->resolve($type);

        return $this->graphQL->type($type);
    }

    public function registerType(string $typeClass): self
    {
        /** @var \Rebing\GraphQL\Support\Type $typeEntity */
        $typeEntity = new $typeClass();
        $this->graphQL->addType($typeEntity);

        $this->typeResolver->addMappingByTypeClass($typeClass, $typeEntity->name);
        if ($typeEntity->model) {
            $this->typeResolver->addMappingByModelClass($typeEntity->model, $typeEntity->name);
        }

        return $this;
    }
}
