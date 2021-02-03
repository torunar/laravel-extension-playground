<?php

namespace App\Schemas\ModelSchema;

use Closure;

class Relation
{
    private string $name;

    private RelationResolverInterface $resolver;

    public function __construct(string $name, RelationResolverInterface $resolver)
    {
        $this->name = $name;
        $this->resolver = $resolver;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getRelatedModel(): string
    {
        return $this->resolver->getRelatedModel();
    }

    public function getResolveCallback(): Closure
    {
        return $this->resolver->getResolver();
    }

    public function getType(): string
    {
        return $this->resolver->getType();
    }
}
