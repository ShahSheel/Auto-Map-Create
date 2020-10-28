<?php

namespace Sheel\Map\Events;

use Sheel\Map\Models\MapCreateMap;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MapCreateMapRestoredEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /** @var MapMapOptions $Map */
    public $Map;

    /**
     * Create a new event instance.
     *
     * @param MapMapOptions $MapOptions
     */
    public function __construct(MapCreateMap $map ) {

        $this->Map = $map;

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
