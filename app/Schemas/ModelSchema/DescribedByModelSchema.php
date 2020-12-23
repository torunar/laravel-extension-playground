<?php

namespace App\Schemas\ModelSchema;

use App\Schemas\ModelSchema\Events\SetModelSchemaEvent;

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
        // TODO: Нужно ли общее событие, или модели должны запускать свои индивидуальные события?
        // TODO: Класс модели может быть свойством схемы. С другой стороны, зачем схеме знать о модели, с которой она связана?
        SetModelSchemaEvent::dispatch(static::class, $schema);
        static::$modelSchema = $schema;
    }
}
