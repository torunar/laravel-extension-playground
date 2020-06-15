<?php

namespace App\Addons\ProductUsergroups\EventSubscribers;

use App\Products\Events\BuildProductColumnsEvent;
use App\Products\Events\GetVisibleProductsEvent;
use App\Products\QueryBuilder;
use Illuminate\Events\Dispatcher;

class ProductEventSubscriber
{
    public function subscribe(Dispatcher $events)
    {
        $events->listen(
            GetVisibleProductsEvent::class,
            fn(GetVisibleProductsEvent $event) => $this->addVisibilityConditions($event)
        );

        $events->listen(
            BuildProductColumnsEvent::class,
            fn(BuildProductColumnsEvent $event) => $this->addColumnSelection($event)
        );
    }

    private function addVisibilityConditions(GetVisibleProductsEvent $event)
    {
        $event->query_builder->where(
            function (QueryBuilder $builder) use ($event) {
                $builder->whereJsonLength('usergroup_ids', 0);
                if ($event->user->usergroup_ids) {
                    $builder->orWhereJsonContains('usergroup_ids', $event->user->usergroup_ids);
                }
            }
        );
    }

    private function addColumnSelection(BuildProductColumnsEvent $event)
    {
        $event->query->selectSub(
            "{$event->query_builder->getModel()->getTable()}.usergroup_ids",
            'usergroup_ids'
        );
    }
}