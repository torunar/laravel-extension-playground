<?php

namespace App\Schemas\ModelSchema;

use Closure;

class Relation
{
    private string $name;

    private Closure $resolver;

    public function __construct(string $name, Closure $resolver)
    {
        $this->name = $name;
        $this->resolver = $resolver;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getResolver(): Closure
    {
        return $this->resolver;
    }
}
