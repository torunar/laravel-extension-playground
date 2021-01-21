<?php

namespace App\Schemas\ValidationSchema;

interface RuleTypeInterface
{
    /**
     * @return string|\Closure|\Illuminate\Contracts\Validation\Rule
     */
    public function getNativeRepresentation();
}
