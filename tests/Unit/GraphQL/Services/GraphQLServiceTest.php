<?php

namespace Tests\Unit\GraphQL\Services;

use App\GraphQL\Services\GraphQLService;
use App\GraphQL\Support\Facades\GraphQL;
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

class GraphQLServiceTest extends TestCase
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
        $actual = GraphQL::getTypeSchemaFromModelSchema($modelSchema);

        $this->assertEquals($expected, $actual);
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
                    ->addAttribute(new TypeAttr('rel_bar', GraphQL::type(BarType::class))),
            ],
            // model with relation one-to-many
            [
                (new ModelSchema())
                    ->addAttribute(new ModelAttr('foo', new PrimitiveAttributeType('int'), true, false))
                    ->addRelation(new Relation('rel_bar', new SimpleRelationResolver(Bar::class, HasMany::class))),
                (new GraphQLTypeSchema())
                    ->addAttribute(new TypeAttr('foo', Type::int()))
                    ->addAttribute(new TypeAttr('rel_bar', Type::listOf(GraphQL::type(BarType::class)))),
            ],
        ];
    }

    private function bootstrapGraphQLService()
    {
        /** @var GraphQLService $gql */
        $this->createApplication()
            ->make(GraphQLService::class)
            ->registerType(BarType::class);
    }
}
