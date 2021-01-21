<?php

namespace App\Schemas\ValidationSchema;

class ValidationRule
{
    protected string $field;

    protected RuleTypeInterface $type;

    public function __construct(string $field, RuleTypeInterface $type)
    {
        $this->field = $field;
        $this->type = $type;
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function getType(): RuleTypeInterface
    {
        return $this->type;
    }
}
