<?php

namespace App\Validation;

use Illuminate\Database\Eloquent\Model;

trait HasModelValidation
{
    public static function bootValidatedModelTrait()
    {
        static::created(
            static function (Model $entity) {
                /** @var ModelValidationServiceInterface $validator */
                $validator = resolve(ModelValidationServiceInterface::class);
                $validation_result = $validator->validate($entity);
                if (!$validation_result->isValid()) {
                    return false;
                }

                return null;
            }
        );
    }

    public function validate()
    {
        /** @var ModelValidationServiceInterface $validator */
        $validator = resolve(ModelValidationServiceInterface::class);
        /** @var Model $self */
        $self = $this;

        return $validator->validate($self);
    }
}