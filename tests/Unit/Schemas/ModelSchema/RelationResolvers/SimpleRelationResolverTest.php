<?php

namespace Tests\Unit\Schemas\ModelSchema\RelationResolvers;

use App\Schemas\ModelSchema\RelationResolvers\SimpleRelationResolver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Tests\TestCase;

class FooSimple extends Model
{
}

class BarSimple extends Model
{
}

class SimpleRelationResolverTest extends TestCase
{
    public function testGeneral()
    {
        $relationResolver = new SimpleRelationResolver(BarSimple::class, HasOne::class);

        $this->assertEquals(BarSimple::class, $relationResolver->getRelatedModel());
        $this->assertEquals(HasOne::class, $relationResolver->getType());
    }

    public function testResolve()
    {
        $foo = new FooSimple();
        $foo::resolveRelationUsing(
            'relationHasOne',
            (new SimpleRelationResolver(BarSimple::class, HasOne::class))->getResolveCallback()
        );
        $foo::resolveRelationUsing(
            'relationHasMany',
            (new SimpleRelationResolver(BarSimple::class, HasMany::class))->getResolveCallback()
        );
        $foo::resolveRelationUsing(
            'relationBelongsTo',
            (new SimpleRelationResolver(BarSimple::class, BelongsTo::class))->getResolveCallback()
        );

        $this->assertInstanceOf(HasOne::class, $foo->relationHasOne());
        $this->assertInstanceOf(HasMany::class, $foo->relationHasMany());
        $this->assertInstanceOf(BelongsTo::class, $foo->relationBelongsTo());
    }
}
