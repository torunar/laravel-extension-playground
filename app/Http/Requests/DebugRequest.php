<?php

namespace App\Http\Requests;

use App\Schemas\RequestValidationSchema\DescribedByRequestValidationSchema;
use App\Schemas\RequestValidationSchema\RequestValidationSchema;
use Illuminate\Foundation\Http\FormRequest;

class DebugRequest extends FormRequest
{
    use DescribedByRequestValidationSchema;

    protected function prepareForValidation()
    {
        $schema = (new RequestValidationSchema());
        static::setRequestValidationSchema($schema);
    }
}
