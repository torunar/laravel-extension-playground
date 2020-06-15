<?php

namespace App\Addons\Warehouses\EventSubscribers;

use App\Products\Events\BuildProductColumnsEvent;
use App\Products\Events\BuildProductWhereInStockQueryEvent;
use Illuminate\Events\Dispatcher;

class ProductEventSubscriber
{
    public function subscribe(Dispatcher $events)
    {
        $events->listen(
            BuildProductWhereInStockQueryEvent::class,
            fn(BuildProductWhereInStockQueryEvent $event) => $this->addInStockConditions($event)
        );

        $events->listen(
            BuildProductColumnsEvent::class,
            fn(BuildProductColumnsEvent $event) => $this->addColumnSelection($event)
        );
    }

    private function addInStockConditions(BuildProductWhereInStockQueryEvent $event)
    {
        $event->query_builder
            ->where(
                static function ($query) {
                    $query->where('stocks.amount', '>', 0)
                        ->orWhere(
                            static function ($query) {
                                $query->where('products.has_amount_split_by_stock', '=', 0)
                                    ->where('products.amount', '>', 0);
                            }
                        );
                }
            );
    }

    private function addColumnSelection(BuildProductColumnsEvent $event)
    {
        $event->query
            ->selectSub(
                "{$event->query_builder->getModel()->getTable()}.has_amount_split_by_stock",
                'has_amount_split_by_stock'
            )
            ->selectSub(
                " CASE WHEN {$event->query_builder->getModel()->getTable()}.has_amount_split_by_stock" .
                "   THEN SUM({$event->query_builder->getModel()->stocks()->getRelated()->getTable()}.amount)" .
                "   ELSE {$event->query_builder->getModel()->getTable()}.amount" .
                " END",
                'amount'
            );
    }
}