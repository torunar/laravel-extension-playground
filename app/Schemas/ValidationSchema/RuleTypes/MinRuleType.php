<?php

namespace App\Schemas\ValidationSchema\RuleTypes;

use App\Schemas\ValidationSchema\UniqueRuleTypeInterface;

class MinRuleType implements UniqueRuleTypeInterface
{
    protected $minValue;

    public function __construct($minValue = 0)
    {
        $this->minValue = $minValue;
    }

    public function getOperation(): string
    {
        return 'min';
    }

    public function getNativeRepresentation(): string
    {
        return "min:{$this->minValue}";
    }
}
