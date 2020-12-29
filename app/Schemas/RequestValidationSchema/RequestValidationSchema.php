<?php

namespace App\Schemas\RequestValidationSchema;

class RequestValidationSchema
{
    /**
     * @var array<string, array<string, \App\Schemas\RequestValidationSchema\ValidationRule>>
     */
    private array $rules = [];

    public function addRule(ValidationRule $rule): self
    {
        if (!isset($this->rules[$rule->getField()])) {
            $this->rules[$rule->getField()] = [];
        }

        $ruleType = $rule->getType();
        if ($ruleType instanceof UniqueRuleTypeInterface) {
            /** @var \App\Schemas\RequestValidationSchema\UniqueRuleTypeInterface $ruleType */
            $this->rules[$rule->getField()][$ruleType->getOperation()] = $rule;
        } else {
            $this->rules[$rule->getField()][] = $rule;
        }

        return $this;
    }

    public function getRules(): array
    {
        $nativeValidatorRulesRepresentation = $this->rules;
        foreach ($nativeValidatorRulesRepresentation as $field => &$rules) {
            foreach ($rules as &$rule) {
                $rule = $rule->getType()->getNativeRepresentation();
            }
            unset($rule);
            $rules = array_values($rules);
        }
        unset($rules);

        return $nativeValidatorRulesRepresentation;
    }
}
