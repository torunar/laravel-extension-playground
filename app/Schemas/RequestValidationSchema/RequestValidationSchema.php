<?php

namespace App\Schemas\RequestValidationSchema;

class RequestValidationSchema
{
    protected array $rules = [];

    public function getRules(): array
    {
        return $this->rules;
    }
}
