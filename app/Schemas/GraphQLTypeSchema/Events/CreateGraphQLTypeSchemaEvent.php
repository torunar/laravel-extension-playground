<?php

namespace App\Schemas\GraphQLTypeSchema\Events;

use App\Schemas\GraphQLTypeSchema\GraphQLTypeSchema;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CreateGraphQLTypeSchemaEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected string $modelClass;

    protected GraphQLTypeSchema $schema;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(string $modelClass, GraphQLTypeSchema $schema)
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
