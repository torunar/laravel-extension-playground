<?php

namespace App\Products\Models;

use App\Schemas\ModelSchema\DescribedByModelSchema;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use DescribedByModelSchema;
}
