<?php

namespace App\Schemas\ModelSchema\RelationResolvers;

use App\Schemas\ModelSchema\RelationResolverInterface;
use Closure;
use Illuminate\Database\Eloquent\Model;

class SimpleRelationResolver implements RelationResolverInterface
{
    protected string $relatedModel;

    protected string $type;

    public function __construct(string $relatedModel, string $type)
    {
        $this->relatedModel = $relatedModel;
        $this->type = $type;
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
        return function (Model $model) {
            $modelMethod = $this->getModelMethodByRelationClassName($this->type);

            return call_user_func([$model, $modelMethod], $this->relatedModel);
        };
    }

    private function getModelMethodByRelationClassName(string $relationClassName): string
    {
        $relationClassNameParts = explode('\\', $relationClassName);
        $methodName = end($relationClassNameParts);

        return lcfirst($methodName);
    }
}
