<?php

namespace Seongbae\Canvas\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\User;

class NewUserCreated
{
    private $user;
    private $args;

    use InteractsWithSockets, SerializesModels;

    public function getUser()
    {
        return $this->user;
    }

    public function getArgs()
    {
        return $this->args;
    }

    /**
     * Create a new event instance.
     * ClientAction constructor.
     * @param Client $client
     * @param $action
     */
    public function __construct(User $user, $args = [])
    {
        $this->user = $user;
        $this->args = $args;
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
