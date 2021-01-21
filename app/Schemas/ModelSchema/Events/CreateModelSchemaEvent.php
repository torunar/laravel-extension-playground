<?php

namespace App\Schemas\ModelSchema\Events;

use App\Schemas\ModelSchema\ModelSchema;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CreateModelSchemaEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected string $modelClass;

    protected ModelSchema $schema;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(string $modelClass, ModelSchema $schema)
    {
        $this->modelClass = $modelClass;
        $this->schema = $schema;
    }

    public function getModelClass(): string
    {
        return $this->modelClass;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel(__NAMESPACE__);
    }
}
