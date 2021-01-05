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

    /**
     * @param array<string, array<string, \App\Schemas\RequestValidationSchema\ValidationRule>> $rules
     *
     * @return $this
     */
    public function setRules(array $rules): self
    {
        $this->rules = $rules;
        return $this;
    }

    public function getRules(): array
    {
        return $this->rules;
    }

    public function getNativeRulesRepresentation(): array
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
