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

    public function getName()
    {
        return $this->name;
    }

    /**
     * @return \Closure
     */
    public function getResolver(): Closure
    {
        return $this->resolver;
    }
}
