<?php

namespace App\GraphQL\Services;

use App\GraphQL\TypeResolver;
use App\Schemas\GraphQLType\Attribute;
use App\Schemas\GraphQLType\GraphQLTypeSchema;
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

    public function getTypeSchemaFromModelSchema(ModelSchema $modelSchema): GraphQLTypeSchema
    {
        $typeSchema = new GraphQLTypeSchema();

        foreach ($modelSchema->getPublicAttributes() as $attribute) {
            $typeSchema->addAttribute(
                new Attribute(
                    $attribute->getName(),
                    $this->type($attribute->getType()->getCast()),
                    $attribute->getName(),
                )
            );
        }

        foreach ($modelSchema->getRelations() as $relation) {
            $relationType = $relation->getType();
            if (!$relationType) {
                continue;
            }

            if (in_array($relationType, self::RELATION_TYPES_THAT_PRODUCE_LISTS)) {
                $typeSchema->addAttribute(
                    new Attribute(
                        $relation->getName(),
                        Type::listOf($this->type($relation->getRelatedEntity())),
                        $relation->getName(),
                    )
                );
            } else {
                $typeSchema->addAttribute(
                    new Attribute(
                        $relation->getName(),
                        $this->type($relation->getRelatedEntity()),
                        $relation->getName(),
                    )
                );
            }
        }

        return $typeSchema;
    }
}
