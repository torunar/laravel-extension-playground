<?php

namespace App\Schemas\ModelSchema\RelationResolvers;

use App\Schemas\ModelSchema\RelationResolverInterface;
use Closure;

class CallableRelationResolver implements RelationResolverInterface
{
    protected string $relatedModel;

    protected string $type;

    protected Closure $resolveCallback;

    public function __construct(string $relatedModel, string $type, Closure $resolveCallback)
    {
        $this->relatedModel = $relatedModel;
        $this->type = $type;
        $this->resolveCallback = $resolveCallback;
    }

    public function getRelatedModel(): string
    {
        return $this->relatedModel;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getResolveCallback(): Closure
    {
        return $this->resolveCallback;
    }
}
