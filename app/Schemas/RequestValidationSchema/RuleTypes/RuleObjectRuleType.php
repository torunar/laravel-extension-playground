<?php

namespace App\Schemas\RequestValidationSchema\RuleTypes;

use App\Schemas\RequestValidationSchema\UniqueRuleTypeInterface;
use Illuminate\Contracts\Validation\Rule;

class RuleObjectRuleType implements UniqueRuleTypeInterface
{
    protected Rule $ruleObject;

    public function __construct(Rule $ruleObject)
    {
        $this->ruleObject = $ruleObject;
    }

    public function getNativeRepresentation(): Rule
    {
        return $this->ruleObject;
    }

    public function getOperation(): string
    {
        return get_class($this->ruleObject);
    }
}
