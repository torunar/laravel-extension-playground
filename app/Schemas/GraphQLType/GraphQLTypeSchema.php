<?php

namespace App\Schemas\GraphQLType;

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
