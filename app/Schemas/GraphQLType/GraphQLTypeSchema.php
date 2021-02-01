<?php

namespace App\Schemas\GraphQLType;

use App\GraphQL\Support\Facades\GraphQL;
use App\Schemas\ModelSchema\ModelSchema;
use GraphQL\Type\Definition\Type;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOneOrMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOneOrMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class GraphQLTypeSchema
{
    /** @var array<string, \App\Schemas\GraphQLType\Attribute> */
    private $attributes;

    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    public function addAttribute(Attribute $attribute): self
    {
        $this->attributes[$attribute->getName()] = $attribute;

        return $this;
    }

    /**
     * @return array<string, \App\Schemas\GraphQLType\Attribute>
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function getFieldsDefinition()
    {
        return array_map(
            static function (Attribute $attribute) {
                return [
                    'type'        => $attribute->getType(),
                    'description' => $attribute->getDescription(),
                    'selectable'  => $attribute->isSelectable(),
                    'resolve'     => $attribute->getResolver(),
                    'privacy'     => $attribute->getPrivacy(),
                ];
            },
            $this->attributes
        );
    }

    public static function createFromModelSchema(ModelSchema $modelSchema)
    {
        $typeSchema = new static();

        foreach ($modelSchema->getAttributes() as $attribute) {
            // hidden attributes shouldn't be exposed to GraphQL types
            if ($attribute->isHidden()) {
                continue;
            }
            $typeSchema->addAttribute(
                new Attribute(
                    $attribute->getName(),
                    GraphQL::type($attribute->getType()->getCast()),
                    $attribute->getName(),
                )
            );
        }

        foreach ($modelSchema->getRelations() as $relation) {
            $resolver = $relation->getResolver();
            $relationType = (new \ReflectionFunction($resolver))->getReturnType();
            if (!$relationType) {
                continue;
            }

            $relationTypesThatProduceLists = [
                HasMany::class,
                HasOneOrMany::class,
                HasManyThrough::class,
                BelongsToMany::class,
                MorphMany::class,
                MorphOneOrMany::class,
                MorphToMany::class,
            ];

            if (in_array($relationType->getName(), $relationTypesThatProduceLists)) {
                $typeSchema->addAttribute(
                    new Attribute(
                        $relation->getName(),
                        Type::listOf(GraphQL::type($relation->getRelatedEntity())),
                        $relation->getName(),
                    )
                );
            } else {
                $typeSchema->addAttribute(
                    new Attribute(
                        $relation->getName(),
                        GraphQL::type($relation->getRelatedEntity()),
                        $relation->getName(),
                    )
                );
            }
        }

        return $typeSchema;
    }

    public function withAttribute(Attribute $attribute): self
    {
        $attributes = $this->getAttributes();
        $attributes[$attribute->getName()] = $attribute;

        return new static($attributes);
    }

    public function getAttribute(string $name): ?Attribute
    {
        if (isset($this->attributes[$name])) {
            return $this->attributes[$name];
        }

        return null;
    }
}
