<?php

namespace App\Schemas\ModelSchema;

trait DescribedByModelSchema
{
    public static ModelSchema $modelSchema;

    public static function bootDescribedByModelSchema()
    {
        static::registerModelEvent(
            'booted',
            static function (self $model) {
                $modelSchema = $model::$modelSchema;

                if ($modelSchema->getTableName()) {
                    $model->setTable($modelSchema->getTableName());
                }
                if ($modelSchema->getKeyName()) {
                    $model->setKeyName($modelSchema->getKeyName());
                }

                /** @var \App\Schemas\ModelSchema\Attribute $attribute */
                foreach ($modelSchema->getAttributes() as $attribute) {
                    $model->casts[$attribute->getName()] = $attribute->getType()->getCast();
                }

                /** @var \App\Schemas\ModelSchema\Relation $relation */
                foreach ($modelSchema->getRelations() as $relation) {
                    static::resolveRelationUsing($relation->getName(), $relation->getResolver());
                }
            }
        );
    }
}
