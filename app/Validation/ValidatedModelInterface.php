<?php

namespace App\Validation;

interface ValidatedModelInterface
{
    public function getModelValidationRules(): array;
}