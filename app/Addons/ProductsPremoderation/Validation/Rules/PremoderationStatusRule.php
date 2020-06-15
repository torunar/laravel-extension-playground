<?php

namespace App\Addons\ProductsPremoderation\Validation\Rules;

use Illuminate\Contracts\Validation\Rule;

class PremoderationStatusRule implements Rule
{
    public function passes($attribute, $value)
    {
        return $value === 'approved'
            || $value === 'rejected';
    }

    public function message()
    {
        return 'Invalid :attribute value specified';
    }
}