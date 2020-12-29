<?php

namespace App\Schemas\ModelSchema\AttributeTypes;

use App\Schemas\ModelSchema\AttributeTypeInterface;

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
