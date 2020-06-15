<?php

namespace App\Validation\Errors;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

class ModelValidationError implements Jsonable, Arrayable
{
    protected string $message;

    protected string $rule_type;

    public function __construct(string $rule_type, string $message)
    {
        $this->rule_type = $rule_type;
        $this->message = $message;
    }

    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }

    public function toArray()
    {
        return [
            'rule_type' => $this->rule_type,
            'message'   => $this->message,
        ];
    }
}