<?php

namespace App\Products\SchemaProviders;

use App\GraphQL\Support\Facades\GraphQL;
use App\Products\Models\Product;
use App\Products\Models\ProductDescription;
use App\Schemas\GraphQLType\Attribute as TypeAttribute;
use App\Schemas\GraphQLType\Events\CreateGraphQLTypeSchemaEvent;
use App\Schemas\GraphQLType\GraphQLTypeSchema;
use App\Schemas\GraphQLType\GraphQLTypeSchemaProviderInterface;
use App\Schemas\ModelSchema\Attribute as ModelAttribute;
use App\Schemas\ModelSchema\AttributeTypes\PrimitiveAttributeType;
use App\Schemas\ModelSchema\Events\CreateModelSchemaEvent;
use App\Schemas\ModelSchema\ModelSchema;
use App\Schemas\ModelSchema\ModelSchemaProviderInterface;
use App\Schemas\ModelSchema\Relation;
use GraphQL\Type\Definition\Type;

class ProductSchemaProvider implements ModelSchemaProviderInterface, GraphQLTypeSchemaProviderInterface
{
    private static ?ModelSchema $modelSchema = null;

    private static ?GraphQLTypeSchema $graphqlTypeSchema = null;

    public static function getModelSchema(): ModelSchema
    {
        if (static::$modelSchema === null) {
            $schema = (new ModelSchema())
                ->setTableName('products')
                ->setKeyName('id')
                ->addAttribute(new ModelAttribute('id', new PrimitiveAttributeType('int')))
                ->addAttribute(new ModelAttribute('sku', new PrimitiveAttributeType('string')))
                ->addAttribute(new ModelAttribute('status', new PrimitiveAttributeType('string')))
                ->addRelation(
                    new Relation(
                        'product_descriptions',
                        static function (Product $product) {
                            return $product->hasMany(ProductDescription::class);
                        }
                    )
                );

            // TODO: Нужно ли общее событие, или схемы должны запускать свои индивидуальные события?
            // TODO: Класс модели может быть свойством схемы. С другой стороны, зачем схеме знать о модели, с которой она связана?
            CreateModelSchemaEvent::dispatch(Product::class, $schema);

            static::$modelSchema = $schema;
        }

        return static::$modelSchema;
    }

    public static function getGraphQLTypeSchema(): GraphQLTypeSchema
    {
        if (static::$graphqlTypeSchema === null) {
            $schema = (new GraphQLTypeSchema())
                ->addAttribute(new TypeAttribute('id', Type::nonNull(Type::int()), 'ID'))
                ->addAttribute(new TypeAttribute('sku', Type::string(), 'SKU'))
                ->addAttribute(new TypeAttribute('status', Type::nonNull(Type::string()), 'Status'))
                ->addAttribute(
                    new TypeAttribute(
                        'product_descriptions',
                        Type::listOf(GraphQL::type(ProductDescription::class)),
                        'Product descriptions'
                    )
                );

            // TODO: Нужно ли общее событие, или схемы должны запускать свои индивидуальные события?
            // TODO: Класс модели может быть свойством схемы. С другой стороны, зачем схеме знать о модели, с которой она связана?
            CreateGraphQLTypeSchemaEvent::dispatch(Product::class, $schema);

            static::$graphqlTypeSchema = $schema;
        }

        return static::$graphqlTypeSchema;
    }
}
