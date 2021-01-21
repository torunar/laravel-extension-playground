<?php

namespace App\Schemas\ValidationSchema;

use App\Schemas\ValidationSchema\Events\SetValidationSchemaEvent;

trait DescribedByValidationSchema
{
    protected static ValidationSchema $requestValidationSchema;

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

    public static function setRequestValidationSchema(ValidationSchema $schema)
    {
        SetValidationSchemaEvent::dispatch(static::class, $schema);
        static::$requestValidationSchema = $schema;
    }
}
