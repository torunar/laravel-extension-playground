<?php

namespace App\Products;

use App\Addons\ProductsPremoderation\Validation\Rules\PremoderationStatusRule;
use App\Addons\ProductsPremoderation\Validation\Rules\ProductRule;
use App\Addons\Warehouses\Behaviors\Products\HasAmountSplitByStock;
use App\Addons\Warehouses\Stock;
use App\Validation\HasModelValidation;
use App\Validation\ValidatedAttributeModelInterface;
use App\Validation\ValidatedModelInterface;
use Illuminate\Database\Eloquent\Model;

class Product extends Model implements ValidatedModelInterface, ValidatedAttributeModelInterface
{
    use HasModelValidation;
    use HasAmountSplitByStock;

    protected $fillable = [
        'sku',
        'price',
        'status',
        'premoderation_status',
        'usergroup_ids',
        'created_at',
        'updated_at',
        'stock'
    ];

    protected $casts = [
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
        'usergroup_ids' => 'json',
        'amount'        => 'integer',
    ];

    public function newEloquentBuilder($query)
    {
        return new QueryBuilder($query);
    }

    public function getAttributeValidationRules(): array
    {
        return [
            'sku'                  => [
                /** product.xml > sku > required */
                'required',
                /** product.xml > sku > type */
                'string',
                /** product.xml > sku > length */
                'min:8',
                'max:128',
                /** product.xml > sku > format */
                'alpha_dash',
            ],
            'premoderation_status' => [
                /** Addons/ProductPremoderation/product.xml > premoderation_status > validValues */
                new PremoderationStatusRule(),
            ],
        ];
    }

    public function getModelValidationRules(): array
    {
        return [
            /** Addons/ProductPremoderation/product.xml > statusMismatch */
            new ProductRule(),
        ];
    }

    public function getAttributeMessages(): array
    {
        return [
            /** product.xml > sku > length */
            'sku.min' => 'Minimum :attribute length is :min',
        ];
    }

    public function getCustomAttributeNames(): array
    {
        return [
            /** product.xml > sku */
            'sku' => 'SKU',
        ];
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }
}
