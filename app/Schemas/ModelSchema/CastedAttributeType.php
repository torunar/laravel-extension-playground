<?php

namespace App\Schemas\ModelSchema;

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
