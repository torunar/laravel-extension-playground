<?php

namespace App\Addons\ProductsPremoderation\EventSubscribers;

use App\Products\Events\BuildProductColumnsEvent;
use App\Products\Events\BuildProductWhereActiveQueryEvent;
use Illuminate\Events\Dispatcher;

class ProductEventSubscriber
{
    public function subscribe(Dispatcher $events)
    {
        $events->listen(
            BuildProductWhereActiveQueryEvent::class,
            fn(BuildProductWhereActiveQueryEvent $event) => $this->addActiveConditions($event)
        );

        $events->listen(
            BuildProductColumnsEvent::class,
            fn(BuildProductColumnsEvent $event) => $this->addColumnSelection($event)
        );
    }

    private function addActiveConditions(BuildProductWhereActiveQueryEvent $event)
    {
        $event->conditions[] = ['premoderation_status', '=', 'approved'];
    }

    private function addColumnSelection(BuildProductColumnsEvent $event)
    {
        $event->query->selectSub(
            "{$event->query_builder->getModel()->getTable()}.premoderation_status",
            'premoderation_status'
        );
    }
}