<?php

namespace App\Addons\Warehouses\ServiceProviders;

use App\Addons\Warehouses\EventSubscribers\ProductEventSubscriber;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as BaseServiceProvider;

class AddonServiceProvider extends BaseServiceProvider
{
    // extend query builder
    protected $subscribe = [
        ProductEventSubscriber::class,
    ];
}