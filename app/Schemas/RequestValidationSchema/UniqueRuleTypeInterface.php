<?php

namespace App\Schemas\RequestValidationSchema;

interface UniqueRuleTypeInterface extends RuleTypeInterface
{
    public function getOperation(): string;
}
