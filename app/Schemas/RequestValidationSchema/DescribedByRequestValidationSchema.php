<?php

namespace App\Schemas\RequestValidationSchema;

use App\Schemas\RequestValidationSchema\Events\SetRequestValidationSchemaEvent;

trait DescribedByRequestValidationSchema
{
    protected static RequestValidationSchema $requestValidationSchema;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return static::$requestValidationSchema
            ? static::$requestValidationSchema->getNativeRulesRepresentation()
            : [];
    }

    public static function setRequestValidationSchema(RequestValidationSchema $schema)
    {
        SetRequestValidationSchemaEvent::dispatch(static::class, $schema);
        static::$requestValidationSchema = $schema;
    }
}
