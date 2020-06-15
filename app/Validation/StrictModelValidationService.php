<?php

namespace App\Validation;

use App\Validation\Errors\AttributeValidationError;
use App\Validation\Errors\ModelValidationError;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;

class StrictModelValidationService implements ModelValidationServiceInterface
{
    public function validate(Model $model): ModelValidationResult
    {
        $model_errors = null;
        $attribute_errors = null;

        if ($model instanceof ValidatedModelInterface) {
            $model_errors = $this->getModelValidationErrors($model);
        }

        if ($model instanceof ValidatedAttributeModelInterface) {
            $attribute_errors = $this->getModelAttributeValidationErrors($model);
        }

        return new ModelValidationResult($model_errors, $attribute_errors);
    }

    protected function getModelValidationErrors(ValidatedModelInterface $model): Collection
    {
        $errors = new Collection();
        $attribute_name = get_class($model);

        foreach ($model->getModelValidationRules() as $model_validation_rule) {
            $validator = Validator::make(
                [$attribute_name => $model],
                [$attribute_name => $model_validation_rule],
            );
            if ($validator->fails()) {
                $validator_errors = $validator->errors()->toArray();
                foreach ($validator->failed() as $failed_rules) {
                    $i = 0;
                    foreach ($failed_rules as $rule_type => $rule_props) {
                        $error = new ModelValidationError(
                            $rule_type,
                            $validator_errors[$attribute_name][$i++]
                        );
                        $errors = $errors->add($error);
                    }
                }
            }
        }

        return $errors;
    }

    protected function getModelAttributeValidationErrors(ValidatedAttributeModelInterface $model): Collection
    {
        $errors = new Collection();

        $validator = Validator::make(
            $model->getAttributes(),
            $model->getAttributeValidationRules(),
            $model->getAttributeMessages(),
            $model->getCustomAttributeNames(),
        );
        if ($validator->fails()) {
            $validator_errors = $validator->errors()->toArray();
            foreach ($validator->failed() as $attribute_name => $failed_rules) {
                $i = 0;
                foreach ($failed_rules as $rule_type => $rule_props) {
                    $error = new AttributeValidationError(
                        $attribute_name,
                        $rule_type,
                        $rule_props,
                        $validator_errors[$attribute_name][$i++]
                    );
                    $errors = $errors->add($error);
                }
            }
        }

        return $errors;
    }
}