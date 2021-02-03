<?php

namespace App\Schemas\ModelSchema;

use Closure;
use Illuminate\Database\Eloquent\Model;

interface RelationResolverInterface
{
    public function getRelatedModel(): string;

    public function getType(): string;

    public function getResolver(): Closure;
}
