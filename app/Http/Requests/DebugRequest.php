<?php

namespace App\Http\Requests;

use App\Schemas\ValidationSchema\DescribedByValidationSchema;
use App\Schemas\ValidationSchema\ValidationSchema;
use Illuminate\Foundation\Http\FormRequest;

class DebugRequest extends FormRequest
{
    use DescribedByValidationSchema;

    protected function prepareForValidation()
    {
        $schema = (new ValidationSchema());
        static::setRequestValidationSchema($schema);
    }
}
