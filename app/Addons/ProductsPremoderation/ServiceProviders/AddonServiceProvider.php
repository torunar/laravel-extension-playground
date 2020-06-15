<?php

namespace App\Addons\ProductsPremoderation\ServiceProviders;

use App\Addons\ProductsPremoderation\EventSubscribers\ProductEventSubscriber;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as BaseServiceProvider;

class AddonServiceProvider extends BaseServiceProvider
{
    // extend query builder
    protected $subscribe = [
        ProductEventSubscriber::class,
    ];
}