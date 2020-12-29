<?php

namespace App\Schemas\RequestValidationSchema\RuleTypes;

use App\Schemas\RequestValidationSchema\RuleTypeInterface;
use Closure;

class ClosureRuleType implements RuleTypeInterface
{
    protected Closure $validatorCallback;

    public function __construct(Closure $validatorCallback)
    {
        $this->validatorCallback = $validatorCallback;
    }

    public function getNativeRepresentation(): Closure
    {
        return $this->validatorCallback;
    }
}
