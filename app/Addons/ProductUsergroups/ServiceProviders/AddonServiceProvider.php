<?php

namespace App\Addons\ProductUsergroups\ServiceProviders;

use App\Addons\ProductUsergroups\EventSubscribers\ProductEventSubscriber;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as BaseServiceProvider;

class AddonServiceProvider extends BaseServiceProvider
{
    // extend query builder
    protected $subscribe = [
        ProductEventSubscriber::class,
    ];
}