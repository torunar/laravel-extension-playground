<?php

namespace App\Schemas\ModelSchema;

trait DescribedByModelSchema
{
    protected static ModelSchema $modelSchema;

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

                foreach ($modelSchema->getAttributes() as $attribute) {
                    $model->casts[$attribute->getName()] = $attribute->getType()->getCast();
                    if ($attribute->isFillable()) {
                        $model->mergeFillable([$attribute->getName()]);
                    }
                    if ($attribute->isHidden()) {
                        $model->makeHidden([$attribute->getName()]);
                    }
                }

                foreach ($modelSchema->getRelations() as $relation) {
                    static::resolveRelationUsing($relation->getName(), $relation->getResolver());
                }
            }
        );
    }

    public static function setModelSchema(ModelSchema $schema)
    {
        static::$modelSchema = $schema;
    }
}
