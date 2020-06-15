<?php

namespace App\Validation;

use Illuminate\Database\Eloquent\Model;

interface ModelValidationServiceInterface
{
    public function validate(Model $model): ModelValidationResult;
}