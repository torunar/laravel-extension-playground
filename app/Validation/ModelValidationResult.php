<?php

namespace App\Validation;

use Illuminate\Support\Collection;

class ModelValidationResult
{
    /**
     * @var \Illuminate\Support\Collection
     */
    public Collection $model_errors;

    /**
     * @var \Illuminate\Support\Collection
     */
    public Collection $attribute_errors;

    public function __construct(?Collection $model_errors = null, ?Collection $attribute_errors = null)
    {
        $this->model_errors = $model_errors ?? new Collection();
        $this->attribute_errors = $attribute_errors ?? new Collection();
    }

    public function isValid(): bool
    {
        return $this->attribute_errors->isEmpty()
            && $this->model_errors->isEmpty();
    }
}