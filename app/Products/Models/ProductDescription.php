<?php

namespace App\Products\Models;

use App\Schemas\ModelSchema\DescribedByModelSchema;
use Illuminate\Database\Eloquent\Model;

class ProductDescription extends Model
{
    use DescribedByModelSchema;

    public $timestamps = false;
}
