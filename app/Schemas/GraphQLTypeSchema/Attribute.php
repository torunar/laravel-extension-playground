<?php

namespace App\Schemas\GraphQLTypeSchema;

use Closure;
use GraphQL\Type\Definition\Type;

class Attribute
{
    protected string $name;

    protected Type $type;

    protected string $description;

    protected bool $isSelectable;

    protected ?Closure $resolver;

    protected ?Closure $privacy;

    public function __construct(
        string $name,
        Type $type,
        string $description,
        Closure $privacy = null,
        Closure $resolver = null,
        bool $isSelectable = true
    ) {
        $this->name = $name;
        $this->type = $type;
        $this->description = $description;
        $this->isSelectable = $isSelectable;
        $this->resolver = $resolver;
        $this->privacy = $privacy;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): Type
    {
        return $this->type;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function isSelectable(): bool
    {
        return $this->isSelectable;
    }

    public function getResolver(): ?Closure
    {
        return $this->resolver;
    }

    public function getPrivacy(): ?Closure
    {
        return $this->privacy;
    }

    public function withDescription(string $description): self
    {
        return new static(
            $this->getName(),
            $this->getType(),
            $description,
            $this->getPrivacy(),
            $this->getResolver(),
            $this->isSelectable(),
        );
    }

    public function withResolver(?Closure $resolver): self
    {
        return new static(
            $this->getName(),
            $this->getType(),
            $this->getDescription(),
            $this->getPrivacy(),
            $resolver,
            $this->isSelectable()
        );
    }

    public function withPrivacy(?Closure $privacy): self
    {
        return new static(
            $this->getName(),
            $this->getType(),
            $this->getDescription(),
            $privacy,
            $this->getResolver(),
            $this->isSelectable(),
        );
    }
}
