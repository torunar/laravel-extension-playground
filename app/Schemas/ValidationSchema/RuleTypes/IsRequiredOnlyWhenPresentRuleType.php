<?php

namespace App\Schemas\ValidationSchema\RuleTypes;

use App\Schemas\ValidationSchema\UniqueRuleTypeInterface;

class IsRequiredOnlyWhenPresentRuleType implements UniqueRuleTypeInterface
{
    public function getOperation(): string
    {
        return $this->getNativeRepresentation();
    }

    public function getNativeRepresentation(): string
    {
        return 'sometimes';
    }
}
