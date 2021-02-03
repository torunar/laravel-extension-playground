<?php

namespace App\GraphQL\Services;

use App\GraphQL\TypeResolver;
use App\Schemas\GraphQLTypeSchema\Attribute;
use App\Schemas\GraphQLTypeSchema\GraphQLTypeSchema;
use App\Schemas\ModelSchema\ModelSchema;
use GraphQL\Type\Definition\Type;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOneOrMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOneOrMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Rebing\GraphQL\GraphQL;

class GraphQLService
{
    protected GraphQL $graphQL;

    protected TypeResolver $typeResolver;

    const RELATION_TYPES_THAT_PRODUCE_LISTS = [
        HasMany::class,
        HasOneOrMany::class,
        HasManyThrough::class,
        BelongsToMany::class,
        MorphMany::class,
        MorphOneOrMany::class,
        MorphToMany::class,
    ];

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

    public function registerQuery(string $queryClass): self
    {
        $this->graphQL->addSchema('default', [
            'query' => [$queryClass],
        ]);

        return $this;
    }

    public function registerMutation(string $mutationClass): self
    {
        $this->graphQL->addSchema('default', [
            'mutation' => [$mutationClass],
        ]);

        return $this;
    }

    public function getTypeSchemaFromModelSchema(ModelSchema $modelSchema): GraphQLTypeSchema
    {
        $typeSchema = new GraphQLTypeSchema();

        foreach ($modelSchema->getPublicAttributes() as $attribute) {
            $typeSchema->addAttribute(
                new Attribute(
                    $attribute->getName(),
                    $this->type($attribute->getType()->getCast()),
                )
            );
        }

        foreach ($modelSchema->getRelations() as $relation) {
            $relationType = $relation->getType();
            if (in_array($relationType, self::RELATION_TYPES_THAT_PRODUCE_LISTS)) {
                $typeSchema->addAttribute(
                    new Attribute(
                        $relation->getName(),
                        Type::listOf($this->type($relation->getRelatedModel())),
                    )
                );
            } else {
                $typeSchema->addAttribute(
                    new Attribute(
                        $relation->getName(),
                        $this->type($relation->getRelatedModel()),
                    )
                );
            }
        }

        return $typeSchema;
    }

    public function hasQuery(string $queryClass): bool
    {
        $schemas = $this->graphQL->getSchemas();

        return in_array($queryClass, $schemas['default']['query']);
    }

    public function hasMutation(string $mutationClass): bool
    {
        $schemas = $this->graphQL->getSchemas();

        return in_array($mutationClass, $schemas['default']['mutation']);
    }

    public function hasType(string $typeClass): bool
    {
        $types = $this->graphQL->getTypes();
        $resolvedType = $this->typeResolver->resolve($typeClass);

        return isset($types[$resolvedType]);
    }
}
