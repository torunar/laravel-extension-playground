<?php

namespace Tests\Unit;

use App\GraphQL\Support\Facades\GraphQL;
use App\Schemas\GraphQLType\Attribute;
use App\Schemas\GraphQLType\GraphQLTypeSchema;
use Tests\TestCase;

class GraphQLTypeSchemaTest extends TestCase
{
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
}
