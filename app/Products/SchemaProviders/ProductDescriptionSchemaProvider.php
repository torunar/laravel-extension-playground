<?php

namespace App\Products\SchemaProviders;

use App\GraphQL\Support\Facades\GraphQL;
use App\Products\Models\Product;
use App\Products\Models\ProductDescription;
use App\Schemas\GraphQLType\Attribute as TypeAttribute;
use App\Schemas\GraphQLType\Events\CreateGraphQLTypeSchemaEvent;
use App\Schemas\GraphQLType\GraphQLTypeSchema;
use App\Schemas\GraphQLType\GraphQLTypeSchemaProviderInterface;
use App\Schemas\ModelSchema\Attribute;
use App\Schemas\ModelSchema\AttributeTypes\PrimitiveAttributeType;
use App\Schemas\ModelSchema\Events\CreateModelSchemaEvent;
use App\Schemas\ModelSchema\ModelSchema;
use App\Schemas\ModelSchema\ModelSchemaProviderInterface;
use App\Schemas\ModelSchema\Relation;
use GraphQL\Type\Definition\Type;

class ProductDescriptionSchemaProvider implements ModelSchemaProviderInterface, GraphQLTypeSchemaProviderInterface
{
    private static ?ModelSchema $modelSchema = null;

    private static ?GraphQLTypeSchema $graphqlTypeSchema = null;

    public static function getModelSchema(): ModelSchema
    {
        if (static::$modelSchema === null) {
            $schema = (new ModelSchema())
                ->setTableName('product_descriptions')
                ->setKeyName('id')
                ->addAttribute(new Attribute('id', new PrimitiveAttributeType('int')))
                ->addAttribute(new Attribute('name', new PrimitiveAttributeType('string')))
                ->addAttribute(new Attribute('description', new PrimitiveAttributeType('string')))
                ->addAttribute(new Attribute('lang_code', new PrimitiveAttributeType('string')))
                ->addRelation(
                    new Relation(
                        'product',
                        static function (ProductDescription $productDescription) {
                            $productDescription->hasOne(Product::class);
                        }
                    )
                );

            // TODO: Нужно ли общее событие, или схемы должны запускать свои индивидуальные события?
            // TODO: Класс модели может быть свойством схемы. С другой стороны, зачем схеме знать о модели, с которой она связана?
            CreateModelSchemaEvent::dispatch(ProductDescription::class, $schema);

            static::$modelSchema = $schema;
        }

        return static::$modelSchema;
    }

    public static function getGraphQLTypeSchema(): GraphQLTypeSchema
    {
        if (static::$graphqlTypeSchema === null) {
            $schema = (new GraphQLTypeSchema())
                ->addAttribute(new TypeAttribute('id', Type::nonNull(Type::int()), 'ID'))
                ->addAttribute(new TypeAttribute('name', Type::nonNull(Type::string()), 'Name'))
                ->addAttribute(new TypeAttribute('description', Type::nonNull(Type::string()), 'Description'))
                ->addAttribute(new TypeAttribute('lang_code', Type::nonNull(Type::string()), 'Language code'))
                ->addAttribute(new TypeAttribute('product', GraphQL::type(Product::class), 'Product'));

            // TODO: Нужно ли общее событие, или схемы должны запускать свои индивидуальные события?
            // TODO: Класс модели может быть свойством схемы. С другой стороны, зачем схеме знать о модели, с которой она связана?
            CreateGraphQLTypeSchemaEvent::dispatch(ProductDescription::class, $schema);

            static::$graphqlTypeSchema = $schema;
        }

        return static::$graphqlTypeSchema;
    }
}
