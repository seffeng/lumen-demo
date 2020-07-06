<?php

namespace App\Modules\User\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use App\Modules\User\Models\User;

class LoginEvent
{
    use InteractsWithSockets, SerializesModels;

    /**
     *
     * @var User
     */
    private $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }

    /**
     *
     * @author zxf
     * @date    2019年10月21日
     * @return \App\Modules\User\Models\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
