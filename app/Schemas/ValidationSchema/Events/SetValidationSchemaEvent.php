<?php

namespace App\Schemas\ValidationSchema\Events;

use App\Schemas\ValidationSchema\ValidationSchema;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SetValidationSchemaEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected string $validatedObjectClass;

    /**
     * @var \App\Schemas\ValidationSchema\ValidationSchema
     */
    protected ValidationSchema $schema;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(string $validatedObjectClass, ValidationSchema $schema)
    {
        $this->validatedObjectClass = $validatedObjectClass;
        $this->schema = $schema;
    }

    public function getValidatedObjectClass(): string
    {
        return $this->validatedObjectClass;
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
