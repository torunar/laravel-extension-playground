<?php

namespace App\Schemas\ModelSchema;

class Attribute
{
    protected string $name;

    /**
     * @var \App\Schemas\ModelSchema\AttributeTypeInterface
     */
    protected AttributeTypeInterface $type;

    public function __construct(string $name, AttributeTypeInterface $type)
    {

        $this->name = $name;
        $this->type = $type;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): AttributeTypeInterface
    {
        return $this->type;
    }
}
