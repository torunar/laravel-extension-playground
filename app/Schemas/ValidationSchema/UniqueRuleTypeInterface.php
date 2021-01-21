<?php

namespace App\Schemas\ValidationSchema;

interface UniqueRuleTypeInterface extends RuleTypeInterface
{
    public function getOperation(): string;
}
