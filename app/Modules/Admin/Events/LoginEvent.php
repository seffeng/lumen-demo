<?php

namespace App\Modules\Admin\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use App\Modules\Admin\Models\Admin;

class LoginEvent
{
    use InteractsWithSockets, SerializesModels;

    /**
     *
     * @var Admin
     */
    private $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Admin $user)
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
     * @return Admin
     */
    public function getUser()
    {
        return $this->user;
    }
}
