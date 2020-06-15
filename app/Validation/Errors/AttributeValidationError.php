<?php

namespace App\Validation\Errors;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

class AttributeValidationError implements Jsonable, Arrayable
{
    protected string $attribute;

    protected array $rule_props;

    protected string $message;

    protected string $rule_type;

    public function __construct(string $attribute, string $rule_type, array $rule_props, string $message)
    {
        $this->attribute = $attribute;
        $this->rule_type = $rule_type;
        $this->rule_props = $rule_props;
        $this->message = $message;
    }

    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }

    public function toArray()
    {
        return [
            'attribute'  => $this->attribute,
            'rule_type'  => $this->rule_type,
            'rule_props' => $this->rule_props,
            'message'    => $this->message,
        ];
    }
}