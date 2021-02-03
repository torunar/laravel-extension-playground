<?php

namespace Tests\Unit\Schemas\GraphQLTypeSchema;

use App\GraphQL\Support\Facades\GraphQL;
use App\Schemas\GraphQLTypeSchema\Attribute;
use App\Schemas\GraphQLTypeSchema\GraphQLTypeSchema;
use Tests\TestCase;

class GraphQLTypeSchemaTest extends TestCase
{
    public function testWithAttribute()
    {
        $initialSchema = new GraphQLTypeSchema([new Attribute('foo', GraphQL::type('int'))]);

        $this->assertEquals(
            new GraphQLTypeSchema([new Attribute('foo', GraphQL::type('int')), new Attribute('bar', GraphQL::type('string'))]),
            $initialSchema->withAttribute(new Attribute('bar', GraphQL::type('string'))),
        );

        $this->assertEquals(
            new GraphQLTypeSchema([new Attribute('foo', GraphQL::type('int'))]),
            $initialSchema,
        );
    }
}
