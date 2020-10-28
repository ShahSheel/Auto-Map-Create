<?php

namespace Sheel\Map\Events;

use Sheel\Map\Models\MapCreateMapImagery;
use Sheel\Map\Models\MapMapOptions;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MapCreaterMapImageryDeletedEvent {

    use Dispatchable, InteractsWithSockets, SerializesModels;

    /** @var MapCreateMapImagery $MapOptions */
    public $MapImagery;

    /**
     * Create a new event instance.
     *
     * @param MapCreateMapImagery $MapOptions
     */
    public function __construct(MapCreateMapImagery $mapImagery ) {

        $this->MapImagery = $mapImagery;

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn() {

        return new PrivateChannel( 'channel-name' );
    }
}
