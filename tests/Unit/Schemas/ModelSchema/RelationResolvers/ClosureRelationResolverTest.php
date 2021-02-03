<?php

namespace Tests\Unit\Schemas\ModelSchema\RelationResolvers;

use App\Schemas\ModelSchema\RelationResolvers\CallableRelationResolver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Tests\TestCase;

class FooClosure extends Model
{
}

class BarClosure extends Model
{
}

class ClosureRelationResolverTest extends TestCase
{
    public function testGeneral()
    {
        $relationResolver = new CallableRelationResolver(BarClosure::class, HasOne::class, static function () { });

        $this->assertEquals(BarClosure::class, $relationResolver->getRelatedModel());
        $this->assertEquals(HasOne::class, $relationResolver->getType());
    }

    public function testResolve()
    {
        $foo = new FooClosure();
        $foo::resolveRelationUsing(
            'relationHasOne',
            (new CallableRelationResolver(
                BarClosure::class, HasOne::class, static function (Model $model) {
                return $model->hasOne(BarClosure::class);
            }
            ))->getResolveCallback()
        );
        $foo::resolveRelationUsing(
            'relationHasMany',
            (new CallableRelationResolver(
                BarClosure::class, HasMany::class, static function (Model $model) {
                return $model->hasMany(BarClosure::class);
            }
            ))->getResolveCallback()
        );
        $foo::resolveRelationUsing(
            'relationBelongsTo',
            (new CallableRelationResolver(
                BarClosure::class, BelongsTo::class, static function (Model $model) {
                return $model->belongsTo(BarClosure::class);
            }
            ))->getResolveCallback()
        );

        $this->assertInstanceOf(HasOne::class, $foo->relationHasOne());
        $this->assertInstanceOf(HasMany::class, $foo->relationHasMany());
        $this->assertInstanceOf(BelongsTo::class, $foo->relationBelongsTo());
    }
}
