<?php

namespace App\Schemas\GraphQLTypeSchema;

class GraphQLTypeSchema
{
    /** @var array<string, \App\Schemas\GraphQLTypeSchema\Attribute> */
    private $attributes;

    public function __construct(array $attributes = [])
    {
        $this->attributes = [];
        foreach ($attributes as $attribute) {
            $this->addAttribute($attribute);
        }
    }

    public function addAttribute(Attribute $attribute): self
    {
        $this->attributes[$attribute->getName()] = $attribute;

        return $this;
    }

    /**
     * @return array<string, \App\Schemas\GraphQLTypeSchema\Attribute>
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
