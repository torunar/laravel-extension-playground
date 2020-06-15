<?php

namespace App\Schemas\ModelSchema;

class PrimitiveAttributeType implements AttributeTypeInterface
{
    private string $primitiveType;

    public function __construct(string $primitiveType)
    {
        $this->primitiveType = $primitiveType;
    }

    public function getCast(): string
    {
        return $this->primitiveType;
    }
}
