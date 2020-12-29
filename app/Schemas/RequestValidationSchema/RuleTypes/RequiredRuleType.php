<?php

namespace App\Schemas\RequestValidationSchema\RuleTypes;

use App\Schemas\RequestValidationSchema\UniqueRuleTypeInterface;

class RequiredRuleType implements UniqueRuleTypeInterface
{
    public function getOperation(): string
    {
        return $this->getNativeRepresentation();
    }

    public function getNativeRepresentation(): string
    {
        return 'required';
    }
}
