<?php

namespace App\Products;

use App\Products\Events\BuildProductColumnsEvent;
use App\Products\Events\BuildProductWhereActiveQueryEvent;
use App\Products\Events\BuildProductWhereInStockQueryEvent;
use Illuminate\Database\Eloquent\Builder;

class QueryBuilder extends Builder
{
    public function whereActive(): self
    {
        $conditions = [
            ["{$this->model->getTable()}.status", '=', 'active'],
        ];

        $event = new BuildProductWhereActiveQueryEvent($this, $conditions);
        event($event);

        $this->where($event->conditions);

        return $this;
    }

    public function whereInStock(): self
    {
        $conditions = [
            ["{$this->model->getTable()}.amount", '>', '0'],
        ];

        $event = new BuildProductWhereInStockQueryEvent($this, $conditions);
        event($event);

        $this->where($event->conditions);

        return $this;
    }

    public function get($columns = ['*'])
    {
        if ($this->getQuery()->columns === null) {
            $this->getQuery()
                ->selectSub("{$this->model->getTable()}.{$this->model->getKeyName()}", 'id')
                ->selectSub("{$this->model->getTable()}.sku", 'sku')
                ->selectSub("{$this->model->getTable()}.price", 'price')
                ->selectSub("{$this->model->getTable()}.status", 'status')
                ->selectSub("{$this->model->getTable()}.amount", 'amount')
                ->selectSub("{$this->model->getTable()}.{$this->model->getCreatedAtColumn()}", 'created_at')
                ->selectSub("{$this->model->getTable()}.{$this->model->getUpdatedAtColumn()}", 'updated_at');

            event(new BuildProductColumnsEvent($this, $this->getQuery()));
        }

        $this->groupBy(['id']);

        return parent::get($columns);
    }
}