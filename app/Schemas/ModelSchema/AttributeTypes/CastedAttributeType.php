<?php

namespace App\Schemas\ModelSchema\AttributeTypes;

use App\Schemas\ModelSchema\AttributeTypeInterface;

class CastedAttributeType implements AttributeTypeInterface
{
    protected string $casterClass;

    public function __construct(string $casterClass)
    {
        $this->casterClass = $casterClass;
    }

    public function getCast()
    {
        return $this->casterClass;
    }
}
