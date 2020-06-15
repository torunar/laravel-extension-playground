<?php

namespace App\Validation;

interface ValidatedAttributeModelInterface
{
    public function getAttributes();

    public function getAttributeValidationRules(): array;

    public function getAttributeMessages(): array;

    public function getCustomAttributeNames(): array;
}