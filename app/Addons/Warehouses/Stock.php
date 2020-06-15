<?php

namespace App\Addons\Warehouses;

use App\Products\Product;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    public $timestamps = false;

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
}
