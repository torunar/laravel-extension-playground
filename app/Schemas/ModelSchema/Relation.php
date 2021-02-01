<?php

namespace App\Schemas\ModelSchema;

use Closure;
use ReflectionFunction;

class Relation
{
    private string $name;

    protected string $relatedEntity;

    private Closure $resolver;

    protected ?string $type;

    public function __construct(string $name, string $relatedEntity, Closure $resolver, string $type = null)
    {
        $this->name = $name;
        $this->relatedEntity = $relatedEntity;
        $this->resolver = $resolver;
        $this->type = $type;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getRelatedEntity(): string
    {
        return $this->relatedEntity;
    }

    public function getResolver(): Closure
    {
        return $this->resolver;
    }

    public function getType(): ?string
    {
        if ($this->type) {
            return $this->type;
        }

        $resolver = $this->getResolver();
        $relationType = (new ReflectionFunction($resolver))->getReturnType();
        if ($relationType) {
            return $this->type = $relationType->getName();
        }

        return null;
    }
}
