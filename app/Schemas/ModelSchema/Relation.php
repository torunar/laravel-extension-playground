<?php

namespace App\Schemas\ModelSchema;

use Closure;

class Relation
{
    private string $name;

    protected string $relatedEntity;

    private Closure $resolver;

    public function __construct(string $name, string $relatedEntity, Closure $resolver)
    {
        $this->name = $name;
        $this->relatedEntity = $relatedEntity;
        $this->resolver = $resolver;
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
}
