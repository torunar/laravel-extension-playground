<?php

namespace App\Schemas\GraphQLType;

use Closure;
use GraphQL\Type\Definition\Type;

class Attribute
{
    protected string $name;

    protected Type $type;

    protected string $description;

    protected ?string $alias;

    protected bool $isSelectable;

    protected ?Closure $resolver;

    protected ?Closure $privacy;

    public function __construct(
        string $name,
        Type $type,
        string $description,
        string $alias = null,
        bool $isSelectable = true,
        Closure $resolver = null,
        Closure $privacy = null
    ) {
        $this->name = $name;
        $this->type = $type;
        $this->description = $description;
        $this->alias = $alias;
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

    public function getAlias(): ?string
    {
        return $this->alias;
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
}
