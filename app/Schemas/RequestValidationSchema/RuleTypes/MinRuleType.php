<?php

namespace App\Schemas\RequestValidationSchema\RuleTypes;

use App\Schemas\RequestValidationSchema\UniqueRuleTypeInterface;

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
