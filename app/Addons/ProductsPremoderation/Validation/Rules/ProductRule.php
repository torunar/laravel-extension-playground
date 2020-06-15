<?php

namespace App\Addons\ProductsPremoderation\Validation\Rules;

use Illuminate\Contracts\Validation\Rule;

class ProductRule implements Rule
{
    /**
     * @param string $attribute
     * @param \App\Products\Product  $value
     *
     * @return bool|void
     */
    public function passes($attribute, $value)
    {
        if (
            $value->status === 'active'
            && $value->premoderation_status !== 'approved'
        ) {
            return false;
        }

        return true;
    }

    public function message()
    {
        return 'Products on moderation can\'t be active';
    }
}