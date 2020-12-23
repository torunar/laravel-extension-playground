<?php

namespace App\Schemas\RequestValidationSchema\Events;

use App\Schemas\RequestValidationSchema\RequestValidationSchema;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SetRequestValidationSchemaEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected string $requestClass;

    /**
     * @var \App\Schemas\RequestValidationSchema\RequestValidationSchema
     */
    protected RequestValidationSchema $schema;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(string $requestClass, RequestValidationSchema $schema)
    {
        $this->requestClass = $requestClass;
        $this->schema = $schema;
    }

    public function getRequestClass(): string
    {
        return $this->requestClass;
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
