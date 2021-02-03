<?php

namespace App\Schemas\ModelSchema\RelationResolvers;

use App\Schemas\ModelSchema\RelationResolverInterface;
use Closure;

class CallableRelationResolver implements RelationResolverInterface
{
    protected string $relatedModel;

    protected string $type;

    protected Closure $resolver;

    public function __construct(string $relatedModel, string $type, Closure $resolver)
    {
        $this->relatedModel = $relatedModel;
        $this->type = $type;
        $this->resolver = $resolver;
    }

    public function getRelatedModel(): string
    {
        return $this->relatedModel;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getResolver(): Closure
    {
        return $this->resolver;
    }
}
