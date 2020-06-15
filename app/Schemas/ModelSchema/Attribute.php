<?php

namespace App\Schemas\ModelSchema;

class Attribute
{
    protected string $name;

    /**
     * @var \App\Schemas\ModelSchema\AttributeTypeInterface
     */
    protected AttributeTypeInterface $type;

    protected bool $isFillable;

    protected bool $isHidden;

    public function __construct(string $name, AttributeTypeInterface $type, bool $isFillable = true, bool $isHidden = false)
    {
        $this->name = $name;
        $this->type = $type;
        $this->isFillable = $isFillable;
        $this->isHidden = $isHidden;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): AttributeTypeInterface
    {
        return $this->type;
    }

    /**
     * @return bool
     */
    public function isFillable(): bool
    {
        return $this->isFillable;
    }

    /**
     * @return bool
     */
    public function isHidden(): bool
    {
        return $this->isHidden;
    }
}
