<?php

namespace Tests\Unit\GraphQL\Services;

use App\GraphQL\Services\GraphQLService;
use App\Schemas\GraphQLTypeSchema\Attribute as TypeAttr;
use App\Schemas\GraphQLTypeSchema\GraphQLTypeSchema;
use App\Schemas\ModelSchema\Attribute as ModelAttr;
use App\Schemas\ModelSchema\AttributeTypes\PrimitiveAttributeType;
use App\Schemas\ModelSchema\ModelSchema;
use App\Schemas\ModelSchema\Relation;
use App\Schemas\ModelSchema\RelationResolvers\SimpleRelationResolver;
use GraphQL\Type\Definition\Type;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Rebing\GraphQL\Support\Mutation;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Tests\CreatesApplication;
use Tests\TestCase;

class Foo extends Model
{
}

class Bar extends Model
{
}

class FooType extends GraphQLType
{
    protected $attributes = [
        'name'        => 'foo',
        'model'       => Foo::class,
    ];
}

class BarType extends GraphQLType
{
    protected $attributes = [
        'name'        => 'bar',
        'model'       => Bar::class,
    ];
}

class FooQuery extends Query
{
    protected $attributes = [
        'name' => 'FooQuery',
    ];

    public function type(): Type
    {
        return Type::int();
    }
}

class BarQuery extends Query
{
    protected $attributes = [
        'name' => 'BarQuery',
    ];

    public function type(): Type
    {
        return Type::int();
    }
}

class FooMutation extends Mutation
{
    protected $attributes = [
        'name' => 'FooMutation',
    ];

    public function type(): Type
    {
        return Type::int();
    }
}

class BarMutation extends Mutation
{
    protected $attributes = [
        'name' => 'BarMutation',
    ];

    public function type(): Type
    {
        return Type::int();
    }
}

class GraphQLServiceTest extends TestCase
{
    use CreatesApplication;

    /**
     * @dataProvider dpCreateFromModelSchema
     */
    public function testCreateFromModelSchema(ModelSchema $modelSchema, GraphQLTypeSchema $expected)
    {
        $gql = $this->getGraphQLService();
        $gql->registerType(BarType::class);

        $actual = $gql->getTypeSchemaFromModelSchema($modelSchema);

        $this->assertEquals($expected, $actual);
    }

    public function testRegisterQuery()
    {
        $gql = $this->getGraphQLService();

        $this->assertFalse($gql->hasQuery(FooQuery::class));
        $this->assertFalse($gql->hasQuery(BarQuery::class));

        $gql->registerQuery(FooQuery::class);
        $this->assertTrue($gql->hasQuery(FooQuery::class));
        $this->assertFalse($gql->hasQuery(BarQuery::class));

        $gql->registerQuery(BarQuery::class);
        $this->assertTrue($gql->hasQuery(FooQuery::class));
        $this->assertTrue($gql->hasQuery(BarQuery::class));
    }

    public function testRegisterMutation()
    {
        $gql = $this->getGraphQLService();

        $this->assertFalse($gql->hasMutation(FooMutation::class));
        $this->assertFalse($gql->hasMutation(BarMutation::class));

        $gql->registerMutation(FooMutation::class);
        $this->assertTrue($gql->hasMutation(FooMutation::class));
        $this->assertFalse($gql->hasMutation(BarMutation::class));

        $gql->registerMutation(BarMutation::class);
        $this->assertTrue($gql->hasMutation(FooMutation::class));
        $this->assertTrue($gql->hasMutation(BarMutation::class));
    }

    public function testRegisterType()
    {
        $gql = $this->getGraphQLService();

        $this->assertFalse($gql->hasType(FooType::class));
        $this->assertFalse($gql->hasType(BarType::class));

        $gql->registerType(FooType::class);
        $this->assertTrue($gql->hasType(FooType::class));
        $this->assertFalse($gql->hasType(BarType::class));

        $gql->registerType(BarType::class);
        $this->assertTrue($gql->hasType(FooType::class));
        $this->assertTrue($gql->hasType(BarType::class));
    }

    public function dpCreateFromModelSchema()
    {
        $gql = $this->getGraphQLService();
        $gql->registerType(BarType::class);

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
                    ->addAttribute(new TypeAttr('foo', Type::int()))
                    ->addAttribute(new TypeAttr('bar', Type::boolean()))
                    ->addAttribute(new TypeAttr('baz', Type::string())),
            ],
            // model with hidden attributes
            [
                (new ModelSchema())
                    ->addAttribute(new ModelAttr('foo', new PrimitiveAttributeType('int'), true, false))
                    ->addAttribute(new ModelAttr('bar', new PrimitiveAttributeType('boolean'), true, false))
                    ->addAttribute(new ModelAttr('baz', new PrimitiveAttributeType('string'), true, true)),
                (new GraphQLTypeSchema())
                    ->addAttribute(new TypeAttr('foo', Type::int()))
                    ->addAttribute(new TypeAttr('bar', Type::boolean())),
            ],
            // model with relation one-to-one
            [
                (new ModelSchema())
                    ->addAttribute(new ModelAttr('foo', new PrimitiveAttributeType('int'), true, false))
                    ->addRelation(new Relation('rel_bar', new SimpleRelationResolver(Bar::class, HasOne::class))),
                (new GraphQLTypeSchema())
                    ->addAttribute(new TypeAttr('foo', Type::int()))
                    ->addAttribute(new TypeAttr('rel_bar', $gql->type(BarType::class))),
            ],
            // model with relation one-to-many
            [
                (new ModelSchema())
                    ->addAttribute(new ModelAttr('foo', new PrimitiveAttributeType('int'), true, false))
                    ->addRelation(new Relation('rel_bar', new SimpleRelationResolver(Bar::class, HasMany::class))),
                (new GraphQLTypeSchema())
                    ->addAttribute(new TypeAttr('foo', Type::int()))
                    ->addAttribute(new TypeAttr('rel_bar', Type::listOf($gql->type(BarType::class)))),
            ],
        ];
    }

    private function getGraphQLService(): GraphQLService
    {
        return $this->createApplication()
            ->make(GraphQLService::class);
    }
}
