<?php

namespace App\Schemas\RequestValidationSchema;

interface RuleTypeInterface
{
    /**
     * @return string|\Closure|\Illuminate\Contracts\Validation\Rule
     */
    public function getNativeRepresentation();
}
