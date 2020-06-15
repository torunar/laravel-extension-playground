<?php

namespace App\Addons\Warehouses;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }
}
