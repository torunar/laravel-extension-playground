<?php

namespace Tests\Unit;

use App\GraphQL\Services\GraphQLService;
use App\GraphQL\Support\Facades\GraphQL;
use App\Schemas\GraphQLType\Attribute;
use App\Schemas\GraphQLType\Attribute as TypeAttr;
use App\Schemas\GraphQLType\GraphQLTypeSchema;
use App\Schemas\ModelSchema\Attribute as ModelAttr;
use App\Schemas\ModelSchema\AttributeTypes\PrimitiveAttributeType;
use App\Schemas\ModelSchema\ModelSchema;
use App\Schemas\ModelSchema\Relation;
use GraphQL\Type\Definition\Type;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Tests\CreatesApplication;
use Tests\TestCase;

class Foo extends Model
{
}

class Bar extends Model
{
}

class BarType extends GraphQLType
{
    protected $attributes = [
        'name'        => 'Bar',
        'description' => 'Bar',
        'model'       => Bar::class,
    ];
}

class GraphQLTypeSchemaTest extends TestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();
        $this->bootstrapGraphQLService();
    }

    /**
     * @dataProvider dpCreateFromModelSchema
     */
    public function testCreateFromModelSchema(ModelSchema $modelSchema, GraphQLTypeSchema $expected)
    {
        $actual = GraphQLTypeSchema::createFromModelSchema($modelSchema);

        $this->assertEquals($expected, $actual);
    }

    public function testWithAttribute()
    {
        $initialModel = (new GraphQLTypeSchema())->addAttribute(new Attribute('foo', GraphQL::type('int'), 'foo'));

        $this->assertEquals(
            (new GraphQLTypeSchema())->addAttribute(new Attribute('foo', GraphQL::type('string'), 'foo')),
            $initialModel->withAttribute(new Attribute('foo', GraphQL::type('string'), 'foo')),
        );

        $this->assertEquals(
            (new GraphQLTypeSchema())->addAttribute(new Attribute('foo', GraphQL::type('int'), 'foo')),
            $initialModel,
        );
    }

    public function dpCreateFromModelSchema()
    {
        $this->bootstrapGraphQLService();

        return [
            // empty model
            [
                new ModelSchema(),
                new GraphQLTypeSchema(),
            ],
            // model with attributes only
            [
                (new ModelSchema())
                    ->addAttribute(new ModelAttr('foo', new PrimitiveAttributeType('int')))
                    ->addAttribute(new ModelAttr('bar', new PrimitiveAttributeType('boolean')))
                    ->addAttribute(new ModelAttr('baz', new PrimitiveAttributeType('string'))),
                (new GraphQLTypeSchema())
                    ->addAttribute(new TypeAttr('foo', Type::int(), 'foo'))
                    ->addAttribute(new TypeAttr('bar', Type::boolean(), 'bar'))
                    ->addAttribute(new TypeAttr('baz', Type::string(), 'baz')),
            ],
            // model with hidden attributes
            [
                (new ModelSchema())
                    ->addAttribute(new ModelAttr('foo', new PrimitiveAttributeType('int'), true, false))
                    ->addAttribute(new ModelAttr('bar', new PrimitiveAttributeType('boolean'), true, false))
                    ->addAttribute(new ModelAttr('baz', new PrimitiveAttributeType('string'), true, true)),
                (new GraphQLTypeSchema())
                    ->addAttribute(new TypeAttr('foo', Type::int(), 'foo'))
                    ->addAttribute(new TypeAttr('bar', Type::boolean(), 'bar')),
            ],
            // model with relation one-to-one
            [
                (new ModelSchema())
                    ->addAttribute(new ModelAttr('foo', new PrimitiveAttributeType('int'), true, false))
                    ->addRelation(
                        new Relation(
                            'rel_bar', Bar::class, static function (Foo $f): HasOne { return $f->hasOne(Bar::class); }
                        )
                    ),
                (new GraphQLTypeSchema())
                    ->addAttribute(new TypeAttr('foo', Type::int(), 'foo'))
                    ->addAttribute(new TypeAttr('rel_bar', GraphQL::type(BarType::class), 'rel_bar')),
            ],
            // model with relation one-to-many
            [
                (new ModelSchema())
                    ->addAttribute(new ModelAttr('foo', new PrimitiveAttributeType('int'), true, false))
                    ->addRelation(
                        new Relation(
                            'rel_bar', Bar::class, static function (Foo $f): HasMany { return $f->HasMany(Bar::class); }
                        )
                    ),
                (new GraphQLTypeSchema())
                    ->addAttribute(new TypeAttr('foo', Type::int(), 'foo'))
                    ->addAttribute(new TypeAttr('rel_bar', Type::listOf(GraphQL::type(BarType::class)), 'rel_bar')),
            ],
            // model with relations without type-hinting
            [
                (new ModelSchema())
                    ->addAttribute(new ModelAttr('foo', new PrimitiveAttributeType('int'), true, false))
                    ->addRelation(
                        new Relation(
                            'rel_bar', Bar::class, static function (Foo $f) { return $f->HasMany(Bar::class); }
                        )
                    ),
                (new GraphQLTypeSchema())
                    ->addAttribute(new TypeAttr('foo', Type::int(), 'foo')),
            ],
        ];
    }

    private function bootstrapGraphQLService()
    {
        /** @var GraphQLService $gql */
        $gql = $this->createApplication()->make(GraphQLService::class);
        $gql->registerType(BarType::class);
    }
}
